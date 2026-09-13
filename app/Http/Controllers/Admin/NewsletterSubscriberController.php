<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', NewsletterSubscriber::class);

        $query = NewsletterSubscriber::query();

        $search = trim((string) ($request->query('q') ?? $request->query('search', '')));
        if ($search !== '') {
            $query->where('email', 'like', "%{$search}%");
        }

        $status = (string) $request->query('status', 'all');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $metrics = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('is_active', true)->count(),
            'this_month' => NewsletterSubscriber::where('subscribed_at', '>=', Carbon::now()->startOfMonth())->count(),
            'today' => NewsletterSubscriber::where('subscribed_at', '>=', Carbon::now()->startOfDay())->count(),
        ];

        $subscribers = $query->latest('subscribed_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.newsletter.index', [
            'subscribers' => $subscribers,
            'metrics' => $metrics,
            'search' => $search,
            'currentStatus' => $status,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        Gate::authorize('export', NewsletterSubscriber::class);

        $status = (string) $request->query('status', 'all');
        $search = trim((string) ($request->query('q') ?? $request->query('search', '')));
        $filename = 'newsletter-inscritos-'.Carbon::now()->format('Y-m-d').'.csv';

        $query = NewsletterSubscriber::query()->latest('subscribed_at');
        if ($search !== '') {
            $query->where('email', 'like', "%{$search}%");
        }
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM para garantir compatibilidade com Microsoft Excel e Google Sheets
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'E-mail',
                'Status',
                'Data de Inscrição',
                'Horário',
                'IP de Origem',
            ], ';');

            $query->chunk(500, function ($subscribers) use ($handle): void {
                foreach ($subscribers as $sub) {
                    fputcsv($handle, [
                        $sub->id,
                        $sub->email,
                        $sub->is_active ? 'Ativo' : 'Inativo',
                        $sub->subscribed_at ? $sub->subscribed_at->format('d/m/Y') : '',
                        $sub->subscribed_at ? $sub->subscribed_at->format('H:i:s') : '',
                        $sub->ip_address ?? 'N/A',
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        Gate::authorize('delete', $subscriber);

        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')
            ->with('success', "Inscrição de {$subscriber->email} removida com sucesso.");
    }
}
