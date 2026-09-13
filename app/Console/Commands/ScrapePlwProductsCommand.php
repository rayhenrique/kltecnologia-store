<?php

namespace App\Console\Commands;

use App\Models\Product;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class ScrapePlwProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-plw 
                            {--page= : Página específica para importar (1 a 7)}
                            {--limit= : Limitar número de produtos a importar}
                            {--delay=200 : Delay em milissegundos entre requisições}
                            {--force : Baixar capa novamente mesmo se já existir localmente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa produtos do catálogo da PLW Design com preços riscados, capas locais e descrições completas';

    public function handle(): int
    {
        $this->info('Iniciando Web Scraper do catálogo da PLW Design...');

        $baseUrl = 'https://vip.plwdesign.online';
        $specificPage = $this->option('page');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $delayMs = (int) $this->option('delay');
        $force = (bool) $this->option('force');

        $pages = $specificPage ? [(int) $specificPage] : range(1, 7);

        File::ensureDirectoryExists(public_path('covers'));

        $totalFound = 0;
        $totalImported = 0;
        $totalErrors = 0;

        foreach ($pages as $page) {
            $catalogUrl = "{$baseUrl}/loja?page={$page}";
            $this->line("<fg=cyan>Buscando página {$page}:</> {$catalogUrl}");

            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                ])->timeout(20)->get($catalogUrl);

                if (! $response->successful()) {
                    $this->error("Falha ao carregar página {$page} (HTTP {$response->status()})");

                    continue;
                }

                $catalogHtml = $response->body();
                $dom = new DOMDocument;
                @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$catalogHtml, LIBXML_NOERROR | LIBXML_NOWARNING);
                $xpath = new DOMXPath($dom);

                $productNodes = $xpath->query("//div[contains(@class, 'product-item')]");
                $this->line("Encontrados {$productNodes->length} produtos na página {$page}.");

                foreach ($productNodes as $node) {
                    if ($limit && $totalImported >= $limit) {
                        $this->warn("Limite de {$limit} produtos atingido. Encerrando importação.");
                        break 2;
                    }

                    $totalFound++;

                    // 1. Extrai link do produto
                    $linkNode = $xpath->query(".//div[contains(@class, 'product-info')]//a[contains(@href, '/produto/')]", $node)->item(0)
                        ?? $xpath->query(".//a[contains(@href, '/produto/')]", $node)->item(0);
                    $productUrl = $linkNode ? $linkNode->getAttribute('href') : null;

                    if (! $productUrl) {
                        continue;
                    }

                    if (! str_starts_with($productUrl, 'http')) {
                        $productUrl = $baseUrl.ltrim($productUrl, '/');
                    }

                    // 2. Extrai título e imagem preliminar
                    $imgNode = $xpath->query(".//figure[contains(@class, 'product-preview-image')]//img", $node)->item(0);
                    $cardTitle = $imgNode ? trim((string) $imgNode->getAttribute('alt')) : null;
                    $cardCoverUrl = $imgNode ? trim((string) $imgNode->getAttribute('src')) : null;

                    // 3. Extrai preço riscado do card (<del>R$ 100,00</del>)
                    $cardDelNode = $xpath->query(".//p[contains(@class, 'price-was-line')]//del", $node)->item(0);
                    $rawCardPrice = $cardDelNode ? trim((string) $cardDelNode->textContent) : null;
                    $parsedPrice = $this->parsePrice($rawCardPrice);

                    // 4. Acessa a página individual do produto para dados completos
                    $productDetails = $this->scrapeProductPage($productUrl, $cardTitle, $cardCoverUrl, $parsedPrice);

                    if ($delayMs > 0) {
                        usleep($delayMs * 1000);
                    }

                    if (! $productDetails) {
                        $totalErrors++;

                        continue;
                    }

                    // 5. Salva imagem localmente
                    $localCoverPath = $this->downloadCoverImage($productDetails['cover_url'], $productDetails['title'], $force);

                    // 6. Cadastra ou atualiza o produto no banco
                    $this->persistProduct($productDetails, $localCoverPath);
                    $totalImported++;

                    $this->info("✓ [{$totalImported}] {$productDetails['title']} | R$ {$productDetails['price']}");
                }
            } catch (Throwable $e) {
                $this->error("Erro ao processar página {$page}: ".$e->getMessage());
                $totalErrors++;
            }
        }

        $this->newLine();
        $this->info('=========================================');
        $this->info('Importação concluída com sucesso!');
        $this->line("Total analisados: {$totalFound}");
        $this->line("Total importados/atualizados: {$totalImported}");
        if ($totalErrors > 0) {
            $this->warn("Total com avisos/erros: {$totalErrors}");
        }
        $this->info('=========================================');

        return Command::SUCCESS;
    }

    /**
     * @return array{title: string, description: string, price: float, cover_url: ?string}|null
     */
    private function scrapeProductPage(string $url, ?string $fallbackTitle, ?string $fallbackCover, ?float $fallbackPrice): ?array
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])->timeout(15)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();
            $dom = new DOMDocument;
            @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($dom);

            // Título completo
            $h1Node = $xpath->query('//h1')->item(0);
            $title = $h1Node ? trim((string) $h1Node->textContent) : $fallbackTitle;
            if (! $title) {
                return null;
            }

            // Imagem de capa em alta resolução
            $coverNode = $xpath->query("//figure[@id='item-gallery-frame']//img[@id='item-cover']")->item(0)
                ?? $xpath->query("//figure[contains(@class, 'item-gallery-frame')]//img")->item(0);
            $coverUrl = $coverNode ? trim((string) $coverNode->getAttribute('src')) : $fallbackCover;

            // Preço riscado (<del>R$ 100,00</del>)
            $delNode = $xpath->query("//div[contains(@class, 'item-club-price')]//del")->item(0)
                ?? $xpath->query('//del')->item(0);
            $rawPrice = $delNode ? trim((string) $delNode->textContent) : null;
            $price = $this->parsePrice($rawPrice) ?? $fallbackPrice ?? 97.00;

            // Descrição detalhada
            $copyNode = $xpath->query("//div[contains(@class, 'item-copy')]")->item(0)
                ?? $xpath->query("//div[contains(@class, 'post-content')]")->item(0);
            $description = $this->formatDescription($copyNode, $title);

            return [
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'cover_url' => $coverUrl,
            ];
        } catch (Throwable) {
            return null;
        }
    }

    private function parsePrice(?string $rawPrice): ?float
    {
        if (! $rawPrice) {
            return null;
        }

        if (preg_match('/([\d\.]+,\d{2})/', $rawPrice, $matches)) {
            return (float) str_replace(['.', ','], ['', '.'], $matches[1]);
        }

        if (preg_match('/(\d+)/', $rawPrice, $matches)) {
            return (float) $matches[1];
        }

        return null;
    }

    private function formatDescription(?\DOMNode $node, string $title): string
    {
        if (! $node) {
            return "Código fonte e arquivos completos do {$title}.\n\nPara suporte e atualizações, consulte o painel de compras.";
        }

        $doc = $node->ownerDocument;
        $innerHtml = '';
        foreach ($node->childNodes as $child) {
            $innerHtml .= $doc->saveHTML($child);
        }

        // Converte tags HTML em quebras de linha e bullets
        $formatted = preg_replace('/<br\s*\/?>/i', "\n", $innerHtml);
        $formatted = preg_replace('/<\/(p|div|h[1-6]|ul|ol|li)>/i', "\n\n", (string) $formatted);
        $formatted = preg_replace('/<li[^>]*>/i', '• ', (string) $formatted);

        $text = trim(strip_tags((string) $formatted));

        // Remove cabeçalhos redundantes como "Descrição do item"
        $text = preg_replace('/^Descrição do item\s*/i', '', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return $text ?: "Código fonte e arquivos completos do {$title}.";
    }

    private function downloadCoverImage(?string $url, string $title, bool $force = false): ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $slug = Str::slug($title);
            $extension = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = "{$slug}.{$extension}";
            $relativePath = "covers/{$filename}";
            $absolutePath = public_path($relativePath);

            if (File::exists($absolutePath) && ! $force && filesize($absolutePath) > 1000) {
                return $relativePath;
            }

            $imgResponse = Http::withoutVerifying()->timeout(20)->get($url);
            if ($imgResponse->successful()) {
                File::put($absolutePath, $imgResponse->body());

                return $relativePath;
            }
        } catch (Throwable) {
            // Em caso de falha no download, retorna nulo sem travar o cadastro
        }

        return null;
    }

    /**
     * @param  array{title: string, description: string, price: float, cover_url: ?string}  $data
     */
    private function persistProduct(array $data, ?string $coverPath): void
    {
        $existing = Product::withTrashed()
            ->where('title', $data['title'])
            ->orWhere('slug', Str::slug($data['title']))
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
            }

            $existing->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => $data['price'],
                'cover_path' => $coverPath ?? $existing->cover_path,
                'is_active' => filled($existing->file_path),
            ]);

            return;
        }

        Product::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'cover_path' => $coverPath,
            'file_path' => null,
            'is_active' => false,
        ]);
    }
}
