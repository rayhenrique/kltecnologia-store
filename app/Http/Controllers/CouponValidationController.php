<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponValidationController extends Controller
{
    public function validateCoupon(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'product_id' => ['nullable', 'integer'],
            'items' => ['nullable', 'array'],
            'items.*' => ['integer'],
        ]);

        $code = strtoupper(trim((string) $data['code']));

        // 1. Resolve produtos elegíveis
        $productIds = [];
        if (filled($data['product_id'] ?? null)) {
            $productIds[] = (int) $data['product_id'];
        }
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (is_numeric($item)) {
                    $productIds[] = (int) $item;
                }
            }
        }

        $products = Product::query()
            ->whereIn('id', array_values(array_unique($productIds)))
            ->where('is_active', true)
            ->get();

        $subtotal = (float) $products->sum('price');

        // 2. Busca o cupom no banco de dados
        $coupon = Coupon::query()->where('code', $code)->first();

        if ($coupon) {
            $evaluation = $coupon->evaluate($products, $subtotal);

            if (! $evaluation['valid']) {
                return response()->json([
                    'valid' => false,
                    'message' => $evaluation['message'],
                ], 422);
            }

            $isFree = ($coupon->discount_type === 'percentage' && (float) $coupon->discount_value >= 100.0)
                || ($subtotal > 0 && $evaluation['discount_amount'] >= $subtotal);

            return response()->json([
                'valid' => true,
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => (float) $coupon->discount_value,
                'discount_amount' => $evaluation['discount_amount'],
                'is_free' => $isFree,
                'message' => $evaluation['message'],
            ]);
        }

        // 3. Fallback para cupons promocionais legados de teste
        if ($code === 'FREE100' || $code === 'GRATIS100') {
            return response()->json([
                'valid' => true,
                'code' => $code,
                'discount_type' => 'percentage',
                'discount_value' => 100.0,
                'discount_amount' => $subtotal,
                'is_free' => true,
                'message' => 'Cupom especial de 100% de desconto aplicado!',
            ]);
        }

        if ($code === 'VIP10' || $code === 'KL10') {
            $discount = round($subtotal * 0.10, 2);

            return response()->json([
                'valid' => true,
                'code' => $code,
                'discount_type' => 'percentage',
                'discount_value' => 10.0,
                'discount_amount' => $discount,
                'is_free' => false,
                'message' => 'Cupom de 10% de desconto aplicado com sucesso!',
            ]);
        }

        if ($code === 'KL2026' || $code === 'PROMO15') {
            $discount = round($subtotal * 0.15, 2);

            return response()->json([
                'valid' => true,
                'code' => $code,
                'discount_type' => 'percentage',
                'discount_value' => 15.0,
                'discount_amount' => $discount,
                'is_free' => false,
                'message' => 'Cupom VIP de 15% de desconto aplicado com sucesso!',
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Cupom inválido ou expirado.',
        ], 422);
    }
}
