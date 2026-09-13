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
        }

        return view('checkout.index', [
            'product' => $product,
            'user' => $request->user(),
        ]);
    }

    public function process(ProcessCheckoutRequest $request): RedirectResponse
    {
        try {
            $result = $this->checkout->process($request->validated(), $request->user());

            if ($result['is_free']) {
                return redirect($result['url'])
                    ->with('success', 'Produto liberado com sucesso! Seu download gratuito já está disponível abaixo.');
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
                    ->with('success', 'Produto liberado com sucesso! Seu download gratuito já está disponível abaixo.');
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

        if ($request->user()) {
            return redirect()->route('customer.downloads')->with('success', $message);
        }

        return redirect()->route('login')->with('success', $message);
    }
}
