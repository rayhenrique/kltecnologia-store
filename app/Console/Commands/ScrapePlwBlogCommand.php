<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\HtmlSanitizerService;
use Carbon\Carbon;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class ScrapePlwBlogCommand extends Command
{
    public function __construct(private readonly HtmlSanitizerService $sanitizer)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-plw-blog
                            {--pages=3 : Quantidade de páginas da listagem do blog a percorrer}
                            {--all : Percorrer todas as páginas disponíveis}
                            {--delay=300 : Atraso em milissegundos entre requisições}
                            {--force : Sobrescrever imagens já existentes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa artigos e tutoriais do blog da PLW Design para o acervo da KL Tecnologia';

    private string $baseUrl = 'https://vip.plwdesign.online';

    public function handle(): int
    {
        $this->info('Iniciando web scraping do Blog da PLW Design...');

        $maxPages = (int) $this->option('pages');
        $scrapeAll = (bool) $this->option('all');
        $delayMs = (int) $this->option('delay');
        $force = (bool) $this->option('force');

        if ($scrapeAll) {
            $maxPages = 15;
        }

        $totalFound = 0;
        $totalImported = 0;
        $totalErrors = 0;

        for ($page = 1; $page <= $maxPages; $page++) {
            $listUrl = "{$this->baseUrl}/blog?page={$page}";
            $this->line("Acessando página {$page}: {$listUrl}");

            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])->timeout(20)->get($listUrl);

                if (! $response->successful()) {
                    $this->warn("Falha ao obter página {$page}: Status {$response->status()}");
                    break;
                }

                $html = $response->body();
                $dom = new DOMDocument;
                @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_NOERROR | LIBXML_NOWARNING);
                $xpath = new DOMXPath($dom);

                $articleNodes = $xpath->query("//article[contains(@class, 'blog-grid-item')]");

                if ($articleNodes->length === 0) {
                    $this->info("Nenhum artigo encontrado na página {$page}. Encerrando paginação.");
                    break;
                }

                $this->info("Encontrados {$articleNodes->length} artigos na página {$page}.");

                foreach ($articleNodes as $node) {
                    $totalFound++;

                    // 1. Extrai link do artigo
                    $linkNode = $xpath->query(".//a[contains(@class, 'blog-grid-cover')]", $node)->item(0)
                        ?? $xpath->query(".//p[contains(@class, 'text-header')]//a", $node)->item(0);

                    $articleUrl = $linkNode ? trim((string) $linkNode->getAttribute('href')) : null;
                    if (! $articleUrl) {
                        continue;
                    }

                    if (! str_starts_with($articleUrl, 'http')) {
                        $articleUrl = $this->baseUrl.ltrim($articleUrl, '/');
                    }

                    // 2. Extrai dados preliminares do card
                    $titleNode = $xpath->query(".//p[contains(@class, 'text-header')]//a", $node)->item(0);
                    $cardTitle = $titleNode ? trim((string) $titleNode->textContent) : null;

                    $imgNode = $xpath->query(".//figure[contains(@class, 'blog-grid-thumb')]//img", $node)->item(0);
                    $cardCoverUrl = $imgNode ? trim((string) $imgNode->getAttribute('src')) : null;

                    $catNode = $xpath->query(".//a[contains(@class, 'blog-meta-cat')]", $node)->item(0);
                    $cardCategory = $catNode ? trim((string) $catNode->textContent) : 'Artigos & Tutoriais';

                    $dateNode = $xpath->query(".//span[contains(@class, 'blog-meta-date')]", $node)->item(0);
                    $cardDateStr = $dateNode ? trim((string) $dateNode->textContent) : null;

                    $excerptNode = $xpath->query(".//div[contains(@class, 'blog-grid-excerpt')]//p", $node)->item(0);
                    $cardExcerpt = $excerptNode ? trim((string) $excerptNode->textContent) : null;

                    // 3. Acessa a página completa do post para capturar o conteúdo completo
                    $postData = $this->scrapeArticlePage($articleUrl, $cardTitle, $cardCoverUrl, $cardCategory, $cardDateStr, $cardExcerpt);

                    if ($delayMs > 0) {
                        usleep($delayMs * 1000);
                    }

                    if (! $postData) {
                        $totalErrors++;

                        continue;
                    }

                    // 4. Faz download da imagem de capa
                    $localCoverPath = $this->downloadCoverImage($postData['cover_url'], $postData['slug'], $force);

                    // 5. Persiste no banco de dados
                    $this->persistPost($postData, $localCoverPath);
                    $totalImported++;

                    $this->info("✓ [{$totalImported}] {$postData['title']} ({$postData['category']})");
                }
            } catch (Throwable $e) {
                $this->error("Erro ao processar página {$page}: ".$e->getMessage());
                $totalErrors++;
            }
        }

        $this->newLine();
        $this->info('=========================================');
        $this->info('Importação do Blog concluída com sucesso!');
        $this->line("Total analisados: {$totalFound}");
        $this->line("Total importados/atualizados: {$totalImported}");
        if ($totalErrors > 0) {
            $this->warn("Total de falhas: {$totalErrors}");
        }
        $this->info('=========================================');

        return Command::SUCCESS;
    }

    /**
     * @return array{title: string, slug: string, category: string, excerpt: string, content: string, published_at: Carbon, cover_url: ?string}|null
     */
    private function scrapeArticlePage(
        string $url,
        ?string $fallbackTitle,
        ?string $fallbackCover,
        string $fallbackCategory,
        ?string $fallbackDate,
        ?string $fallbackExcerpt
    ): ?array {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])->timeout(20)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();
            $dom = new DOMDocument;
            @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($dom);

            // Título
            $h1Node = $xpath->query('//h1')->item(0);
            $title = $h1Node ? trim((string) $h1Node->textContent) : $fallbackTitle;
            if (! $title) {
                return null;
            }

            $slug = Str::slug($title);

            // Imagem de capa
            $coverNode = $xpath->query("//figure[contains(@class, 'blog-post-cover')]//img")->item(0)
                ?? $xpath->query("//div[contains(@class, 'post-image')]//img")->item(0);
            $coverUrl = $coverNode ? trim((string) $coverNode->getAttribute('src')) : $fallbackCover;

            // Categoria
            $catNode = $xpath->query("//a[contains(@class, 'blog-meta-cat')]")->item(0);
            $category = $catNode ? trim((string) $catNode->textContent) : $fallbackCategory;

            // Data
            $dateNode = $xpath->query("//span[contains(@class, 'blog-meta-date')]")->item(0);
            $dateStr = $dateNode ? trim((string) $dateNode->textContent) : $fallbackDate;
            $publishedAt = $this->parsePtDate($dateStr);

            // Conteúdo principal
            $contentNode = $xpath->query("//div[contains(@class, 'legacy-html')]")->item(0)
                ?? $xpath->query("//div[contains(@class, 'post-paragraph')]")->item(0);

            $content = $this->cleanArticleContent($contentNode, $title);

            // Resumo / Excerpt
            $leadNode = $xpath->query("//section[contains(@class, 'plw-bap-lead')]//p", $contentNode)->item(0);
            $excerpt = $leadNode ? trim((string) $leadNode->textContent) : $fallbackExcerpt;
            if (! $excerpt) {
                $excerpt = Str::limit(strip_tags($content), 180);
            }

            return [
                'title' => $title,
                'slug' => $slug,
                'category' => $category,
                'excerpt' => $excerpt,
                'content' => $content,
                'published_at' => $publishedAt,
                'cover_url' => $coverUrl,
            ];
        } catch (Throwable) {
            return null;
        }
    }

    private function cleanArticleContent(?DOMNode $node, string $title): string
    {
        if (! $node) {
            return "<p>Confira as novidades e orientações práticas sobre {$title} no acervo da KL Tecnologia.</p>";
        }

        $doc = $node->ownerDocument;
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $doc->saveHTML($child);
        }

        // Substitui referências à PLW Design por KL Tecnologia
        $html = str_ireplace(
            ['Clube VIP PLW Design', 'PLW Design', 'https://vip.plwdesign.online', 'https://plwdesign.online'],
            ['KL Tecnologia', 'KL Tecnologia', '/loja', '/loja'],
            $html
        );

        // Remove CTAs externos ou botões com links da PLW se houver
        $html = preg_replace('/href="[^"]*plwdesign[^"]*"/i', 'href="/loja"', $html);

        $sanitized = $this->sanitizer->sanitize($html);

        return $sanitized !== '' ? $sanitized : '<p>Artigo indisponível.</p>';
    }

    private function parsePtDate(?string $dateStr): Carbon
    {
        if (! $dateStr) {
            return now();
        }

        $months = [
            'janeiro' => '01', 'fevereiro' => '02', 'março' => '03', 'marco' => '03',
            'abril' => '04', 'maio' => '05', 'junho' => '06',
            'julho' => '07', 'agosto' => '08', 'setembro' => '09',
            'outubro' => '10', 'novembro' => '11', 'dezembro' => '12',
        ];

        if (preg_match('/(\d{1,2})\s+de\s+([a-zç]+)\s+de\s+(\d{4})/i', mb_strtolower($dateStr), $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = $months[$matches[2]] ?? '01';
            $year = $matches[3];

            try {
                return Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-{$day}");
            } catch (Throwable) {
                return now();
            }
        }

        return now();
    }

    private function downloadCoverImage(?string $url, string $slug, bool $force = false): ?string
    {
        if (! $url) {
            return null;
        }

        $directory = public_path('blog_covers');
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $extension = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION);
        $extension = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']) ? strtolower($extension) : 'jpg';
        $filename = "{$slug}.{$extension}";
        $localPath = "blog_covers/{$filename}";
        $fullPath = public_path($localPath);

        if (! $force && File::exists($fullPath) && File::size($fullPath) > 500) {
            return $localPath;
        }

        try {
            $response = Http::withoutVerifying()->timeout(15)->get($url);
            if ($response->successful() && strlen($response->body()) > 200) {
                File::put($fullPath, $response->body());

                return $localPath;
            }
        } catch (Throwable) {
            // Em caso de erro no download da capa externa, mantém null
        }

        return null;
    }

    /**
     * @param  array{title: string, slug: string, category: string, excerpt: string, content: string, published_at: Carbon, cover_url: ?string}  $data
     */
    private function persistPost(array $data, ?string $coverPath): Post
    {
        return Post::updateOrCreate(
            ['slug' => $data['slug']],
            [
                'title' => $data['title'],
                'category' => $data['category'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'cover_path' => $coverPath,
                'is_published' => true,
                'published_at' => $data['published_at'],
            ]
        );
    }
}
