<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateCouponRequest;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CouponValidationController extends Controller
{
    public function validateCoupon(ValidateCouponRequest $request): JsonResponse
    {
        $data = $request->validated();

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
            ->availableForSale()
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

        return response()->json([
            'valid' => false,
            'message' => 'Cupom inválido ou expirado.',
        ], 422);
    }
}
