<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\PaymentReturnRequest;
use App\Models\Product;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(private readonly CheckoutService $checkout) {}

    public function store(CheckoutRequest $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        try {
            return redirect()->away($this->checkout->start($request->user(), $product));
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

        return redirect()->route('customer.downloads')->with('success', $message);
    }
}
