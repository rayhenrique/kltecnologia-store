<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrafficAnalyticsService
{
    /**
     * Registra um acesso a página de forma assíncrona/segura.
     */
    public function record(Request $request): void
    {
        try {
            $routeName = $request->route()?->getName();
            $path = '/'.ltrim($request->path(), '/');

            $viewable = $this->resolveViewable($request, $routeName);

            $ip = (string) ($request->ip() ?? '127.0.0.1');
            $userAgent = (string) ($request->userAgent() ?? 'unknown');
            $visitorHash = hash('sha256', $ip.'|'.$userAgent.'|'.Carbon::today()->toDateString());

            $referer = $request->header('referer');
            if (is_string($referer)) {
                $referer = mb_substr(trim($referer), 0, 500);
            } else {
                $referer = null;
            }

            $deviceType = $this->detectDevice($userAgent);

            PageView::create([
                'url' => mb_substr($path, 0, 500),
                'route_name' => $routeName ? mb_substr($routeName, 0, 100) : null,
                'viewable_type' => $viewable ? $viewable->getMorphClass() : null,
                'viewable_id' => $viewable?->getKey(),
                'visitor_hash' => $visitorHash,
                'referer' => $referer,
                'device_type' => $deviceType,
                'visited_at' => Carbon::now(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Erro silencioso ao registrar PageView no TrafficAnalyticsService', [
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Retorna o consolidado de métricas para o Dashboard Administrativo.
     *
     * @return array<string, mixed>
     */
    public function getDashboardMetrics(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Visitas e Visitantes Únicos Hoje
        $todayViews = PageView::query()->whereDate('visited_at', $today)->count();
        $todayUniques = PageView::query()->whereDate('visited_at', $today)->distinct('visitor_hash')->count('visitor_hash');

        // 2. Visitas Ontem
        $yesterdayViews = PageView::query()->whereDate('visited_at', $yesterday)->count();

        // 3. Métricas do Mês
        $monthViews = PageView::query()->where('visited_at', '>=', $startOfMonth)->count();
        $monthUniques = PageView::query()->where('visited_at', '>=', $startOfMonth)->distinct('visitor_hash')->count('visitor_hash');

        // 4. Taxa de Conversão do Mês
        $paidOrdersMonth = Order::query()
            ->where('status', OrderStatus::Paid)
            ->where('created_at', '>=', $startOfMonth)
            ->count();

        $conversionRate = $monthUniques > 0
            ? round(($paidOrdersMonth / $monthUniques) * 100, 1)
            : 0.0;

        // 5. Histórico dos Últimos 14 Dias (Para Gráfico Interativo)
        $dailyChart = $this->buildDailyChart(14);

        // 6. Proporção de Dispositivos no Mês
        $deviceBreakdown = $this->calculateDeviceBreakdown($startOfMonth);

        // 7. Top 5 Produtos Mais Acessados (Últimos 30 Dias)
        $topProducts = $this->getTopProducts(30, 5);

        // 8. Top 5 Artigos Mais Lidos do Blog
        $topPosts = $this->getTopPosts(30, 5);

        return [
            'todayViews' => $todayViews,
            'todayUniques' => $todayUniques,
            'yesterdayViews' => $yesterdayViews,
            'monthViews' => $monthViews,
            'monthUniques' => $monthUniques,
            'paidOrdersMonth' => $paidOrdersMonth,
            'conversionRate' => $conversionRate,
            'dailyChart' => $dailyChart,
            'deviceBreakdown' => $deviceBreakdown,
            'topProducts' => $topProducts,
            'topPosts' => $topPosts,
        ];
    }

    /**
     * Resolve o modelo Product ou Post quando a rota acessada for específica.
     */
    private function resolveViewable(Request $request, ?string $routeName): mixed
    {
        if ($routeName === 'storefront.show') {
            $productParam = $request->route('product');
            if ($productParam instanceof Product) {
                return $productParam;
            }
            if (is_string($productParam)) {
                return Product::query()->where('slug', $productParam)->first();
            }
        }

        if ($routeName === 'blog.show') {
            $postParam = $request->route('post');
            if ($postParam instanceof Post) {
                return $postParam;
            }
            if (is_string($postParam)) {
                return Post::query()->where('slug', $postParam)->first();
            }
        }

        return null;
    }

    /**
     * Detecta se o acesso é mobile, tablet ou desktop com base no User-Agent.
     */
    private function detectDevice(string $userAgent): string
    {
        $ua = strtolower($userAgent);

        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet') || str_contains($ua, 'playbook') || str_contains($ua, 'silk')) {
            return 'tablet';
        }

        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone') || str_contains($ua, 'ipod') || str_contains($ua, 'blackberry') || str_contains($ua, 'windows phone')) {
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * Constrói o array cronológico dos últimos X dias para o gráfico.
     *
     * @return array<int, array{date: string, fullDate: string, views: int, uniques: int, heightPercent: int}>
     */
    private function buildDailyChart(int $days = 14): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $rows = PageView::query()
            ->where('visited_at', '>=', $startDate)
            ->selectRaw('DATE(visited_at) as date_group, COUNT(*) as total_views, COUNT(DISTINCT visitor_hash) as total_uniques')
            ->groupBy('date_group')
            ->get()
            ->keyBy('date_group');

        $chart = [];
        $maxViews = 1;

        for ($i = $days - 1; $i >= 0; $i--) {
            $currentDate = Carbon::now()->subDays($i);
            $key = $currentDate->toDateString();
            $views = isset($rows[$key]) ? (int) $rows[$key]->total_views : 0;
            $uniques = isset($rows[$key]) ? (int) $rows[$key]->total_uniques : 0;

            if ($views > $maxViews) {
                $maxViews = $views;
            }

            $chart[] = [
                'date' => $currentDate->format('d/m'),
                'fullDate' => $currentDate->format('d/m/Y'),
                'views' => $views,
                'uniques' => $uniques,
                'heightPercent' => 0, // calculado a seguir
            ];
        }

        // Calcula a porcentagem de altura relativa de cada barra (mínimo 6% para visibilidade)
        foreach ($chart as &$point) {
            $point['heightPercent'] = $point['views'] > 0
                ? max(6, (int) round(($point['views'] / $maxViews) * 100))
                : 0;
        }
        unset($point);

        return $chart;
    }

    /**
     * @return array{desktop: int, mobile: int}
     */
    private function calculateDeviceBreakdown(Carbon $startDate): array
    {
        $deviceCounts = PageView::query()
            ->where('visited_at', '>=', $startDate)
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type');

        $desktop = (int) ($deviceCounts['desktop'] ?? 0);
        $mobile = (int) (($deviceCounts['mobile'] ?? 0) + ($deviceCounts['tablet'] ?? 0));
        $total = $desktop + $mobile;

        if ($total === 0) {
            return ['desktop' => 70, 'mobile' => 30]; // default inicial harmônico
        }

        return [
            'desktop' => (int) round(($desktop / $total) * 100),
            'mobile' => (int) round(($mobile / $total) * 100),
        ];
    }

    /**
     * @return Collection<int, array{product: Product, views: int}>
     */
    private function getTopProducts(int $days = 30, int $limit = 5): Collection
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $records = PageView::query()
            ->where('viewable_type', (new Product)->getMorphClass())
            ->where('visited_at', '>=', $startDate)
            ->selectRaw('viewable_id, COUNT(*) as views_count')
            ->groupBy('viewable_id')
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();

        if ($records->isEmpty()) {
            // Fallback: se ainda não houver acessos gravados, traz os produtos recentes
            return Product::query()
                ->where('is_active', true)
                ->latest('id')
                ->limit($limit)
                ->get()
                ->map(fn (Product $p) => ['product' => $p, 'views' => 0]);
        }

        $products = Product::query()->whereIn('id', $records->pluck('viewable_id'))->get()->keyBy('id');

        return $records->map(function ($record) use ($products) {
            $product = $products->get($record->viewable_id);
            if (! $product) {
                return null;
            }

            return [
                'product' => $product,
                'views' => (int) $record->views_count,
            ];
        })->filter()->values();
    }

    /**
     * @return Collection<int, array{post: Post, views: int}>
     */
    private function getTopPosts(int $days = 30, int $limit = 5): Collection
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $records = PageView::query()
            ->where('viewable_type', (new Post)->getMorphClass())
            ->where('visited_at', '>=', $startDate)
            ->selectRaw('viewable_id, COUNT(*) as views_count')
            ->groupBy('viewable_id')
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();

        if ($records->isEmpty()) {
            // Fallback: traz os posts publicados com ordenação por views_count ou recentes
            return Post::query()
                ->where('is_published', true)
                ->with('blogCategory')
                ->orderByDesc('views_count')
                ->latest('id')
                ->limit($limit)
                ->get()
                ->map(fn (Post $p) => ['post' => $p, 'views' => (int) $p->views_count]);
        }

        $posts = Post::query()->with('blogCategory')->whereIn('id', $records->pluck('viewable_id'))->get()->keyBy('id');

        return $records->map(function ($record) use ($posts) {
            $post = $posts->get($record->viewable_id);
            if (! $post) {
                return null;
            }

            return [
                'post' => $post,
                'views' => (int) $record->views_count,
            ];
        })->filter()->values();
    }
}
