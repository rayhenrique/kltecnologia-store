<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsletterSubscriberRequest;
use App\Http\Requests\Admin\UpdateNewsletterSubscriberRequest;
use App\Http\Requests\ListFilterRequest;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    public function index(ListFilterRequest $request): View
    {
        Gate::authorize('viewAny', NewsletterSubscriber::class);

        $query = NewsletterSubscriber::query();

        $search = trim((string) ($request->validated('q') ?? $request->validated('search', '')));
        if ($search !== '') {
            $query->where('email', 'like', "%{$search}%");
        }

        $status = (string) $request->validated('status', 'all');
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

    public function create(): View
    {
        Gate::authorize('create', NewsletterSubscriber::class);

        return view('admin.newsletter.create');
    }

    public function store(StoreNewsletterSubscriberRequest $request): RedirectResponse
    {
        Gate::authorize('create', NewsletterSubscriber::class);

        $data = $request->validated();
        $email = strtolower(trim((string) $data['email']));
        $isActive = $request->boolean('is_active', true);
        $subscribedAt = ! empty($data['subscribed_at']) ? Carbon::parse($data['subscribed_at']) : Carbon::now();

        $subscriber = NewsletterSubscriber::withTrashed()->where('email', $email)->first();

        if ($subscriber) {
            $subscriber->restore();
            $subscriber->update([
                'is_active' => $isActive,
                'subscribed_at' => $subscribedAt,
                'unsubscribed_at' => $isActive ? null : Carbon::now(),
                'ip_address' => $subscriber->ip_address ?? $request->ip() ?? 'Admin Manual',
                'user_agent' => $subscriber->user_agent ?? 'Painel Admin',
            ]);
        } else {
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'is_active' => $isActive,
                'subscribed_at' => $subscribedAt,
                'unsubscribed_at' => $isActive ? null : Carbon::now(),
                'ip_address' => $request->ip() ?? 'Admin Manual',
                'user_agent' => 'Painel Admin',
            ]);
        }

        return redirect()->route('admin.newsletter.show', $subscriber)
            ->with('success', "Inscrito {$subscriber->email} cadastrado com sucesso!");
    }

    public function show(NewsletterSubscriber $subscriber): View
    {
        Gate::authorize('view', $subscriber);

        $subscriber->load([
            'sendLogs' => fn ($q) => $q->latest('sent_at')->with('notifiable'),
            'user',
        ]);

        return view('admin.newsletter.show', [
            'subscriber' => $subscriber,
        ]);
    }

    public function edit(NewsletterSubscriber $subscriber): View
    {
        Gate::authorize('update', $subscriber);

        return view('admin.newsletter.edit', [
            'subscriber' => $subscriber,
        ]);
    }

    public function update(UpdateNewsletterSubscriberRequest $request, NewsletterSubscriber $subscriber): RedirectResponse
    {
        Gate::authorize('update', $subscriber);

        $data = $request->validated();
        $isActive = $request->boolean('is_active');
        $subscribedAt = ! empty($data['subscribed_at']) ? Carbon::parse($data['subscribed_at']) : $subscriber->subscribed_at;

        $unsubscribedAt = $subscriber->unsubscribed_at;
        if ($isActive && ! $subscriber->is_active) {
            $unsubscribedAt = null;
        } elseif (! $isActive && $subscriber->is_active) {
            $unsubscribedAt = Carbon::now();
        }

        $subscriber->update([
            'email' => strtolower(trim((string) $data['email'])),
            'is_active' => $isActive,
            'subscribed_at' => $subscribedAt,
            'unsubscribed_at' => $unsubscribedAt,
        ]);

        return redirect()->route('admin.newsletter.show', $subscriber)
            ->with('success', "Inscrição de {$subscriber->email} atualizada com sucesso!");
    }

    public function toggleStatus(NewsletterSubscriber $subscriber): RedirectResponse
    {
        Gate::authorize('update', $subscriber);

        $subscriber->is_active = ! $subscriber->is_active;
        $subscriber->unsubscribed_at = $subscriber->is_active ? null : Carbon::now();
        $subscriber->save();

        $statusLabel = $subscriber->is_active ? 'ativada' : 'desativada';

        return back()->with('success', "Inscrição de {$subscriber->email} foi {$statusLabel} com sucesso.");
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        Gate::authorize('delete', $subscriber);

        $email = $subscriber->email;
        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')
            ->with('success', "Inscrição de {$email} removida com sucesso.");
    }

    public function export(ListFilterRequest $request): StreamedResponse
    {
        Gate::authorize('export', NewsletterSubscriber::class);

        $status = (string) $request->validated('status', 'all');
        $search = trim((string) ($request->validated('q') ?? $request->validated('search', '')));
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
}
