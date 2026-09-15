<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\ListFilterRequest;
use App\Http\Requests\PaymentReturnRequest;
use App\Http\Requests\ProcessCheckoutRequest;
use App\Models\Product;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(private readonly CheckoutService $checkout) {}

    public function index(ListFilterRequest $request): View
    {
        $product = null;
        if ($request->filled('product')) {
            $product = Product::where('slug', (string) $request->validated('product'))
                ->availableForSale()
                ->first();
        } elseif (old('product_id')) {
            $product = Product::where('id', old('product_id'))
                ->availableForSale()
                ->first();
        }

        $oldItems = collect();
        if (old('items') && is_array(old('items'))) {
            $oldItems = Product::query()
                ->whereIn('id', old('items'))
                ->availableForSale()
                ->get()
                ->map(fn (Product $p) => [
                    'id' => $p->id,
                    'title' => $p->title,
                    'slug' => $p->slug,
                    'price' => (float) $p->price,
                    'cover_image' => $p->cover_image,
                    'category' => $p->categoryGroup?->name ?? $p->category ?? 'Sistema Web',
                ]);
        }

        return view('checkout.index', [
            'product' => $product,
            'oldItems' => $oldItems,
            'user' => $request->user(),
        ]);
    }

    public function process(ProcessCheckoutRequest $request): RedirectResponse
    {
        try {
            $result = $this->checkout->process($request->validated(), $request->user());

            if ($result['is_free']) {
                return redirect($result['url'])
                    ->with('success', 'Produto liberado com sucesso! Seu download gratuito já está disponível abaixo.')
                    ->with('clear_cart', true);
            }

            return redirect()->away($result['url']);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput($request->safe()->except(['password', 'password_confirmation']))
                ->with('error', 'Não foi possível processar seu pedido. Por favor, verifique seus dados ou tente novamente em instantes.');
        }
    }

    public function store(CheckoutRequest $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active && $product->file_path, 404);

        try {
            $result = $this->checkout->start($request->user(), $product);

            if ($result['is_free']) {
                return redirect($result['url'])
                    ->with('success', 'Produto liberado com sucesso! Seu download gratuito já está disponível abaixo.')
                    ->with('clear_cart', true);
            }

            return redirect()->away($result['url']);
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'Não foi possível abrir o pagamento agora. Tente novamente em instantes.');
        }
    }

    public function return(PaymentReturnRequest $request): RedirectResponse
    {
        $message = match ($request->validated('status')) {
            'success' => 'Pagamento recebido. A liberação aparece assim que o Mercado Pago confirmar.',
            'pending' => 'Pagamento pendente. Seu download será liberado após a confirmação.',
            default => 'O pagamento não foi concluído. Você pode tentar novamente.',
        };

        $isSuccessful = in_array($request->validated('status'), ['success', 'pending'], true);

        if ($request->user()) {
            return redirect()->route('customer.downloads')
                ->with('success', $message)
                ->with('clear_cart', $isSuccessful);
        }

        return redirect()->route('login')
            ->with('success', $message)
            ->with('clear_cart', $isSuccessful);
    }
}
