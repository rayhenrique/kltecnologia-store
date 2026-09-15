<?php

namespace App\Http\Middleware;

use App\Services\TrafficAnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViews
{
    public function __construct(
        private readonly TrafficAnalyticsService $trafficAnalytics,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        if (app()->runningUnitTests()) {
            $this->trafficAnalytics->record($request);
        } else {
            dispatchAfterResponse(function () use ($request) {
                $this->trafficAnalytics->record($request);
            });
        }

        return $response;
    }

    /**
     * Determina se a requisição atual deve ser rastreada.
     */
    private function shouldTrack(Request $request, Response $response): bool
    {
        // Rastreia apenas requisições GET com resposta de sucesso (200 OK)
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }

        // Ignora administradores autenticados para não inflar métricas
        if (Auth::check() && Auth::user()?->isAdmin()) {
            return false;
        }

        $path = ltrim($request->path(), '/');

        // Rotas internas, utilitárias ou administrativas que não devem ser contabilizadas
        $ignoredPrefixes = [
            'admin',
            'webhooks',
            'customer/downloads',
            'build',
            'covers',
            'blog_covers',
            'images',
            'fonts',
            'storage',
            'up',
            'livewire',
        ];

        foreach ($ignoredPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return false;
            }
        }

        $ignoredFiles = [
            'sitemap.xml',
            'robots.txt',
            'favicon.ico',
            'favicon.png',
        ];

        if (in_array($path, $ignoredFiles, true)) {
            return false;
        }

        return true;
    }
}
