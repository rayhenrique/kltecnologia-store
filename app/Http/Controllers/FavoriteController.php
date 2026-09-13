<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $recommendedProducts = Product::query()
            ->where('is_active', true)
            ->with('categoryGroup')
            ->latest('id')
            ->take(4)
            ->get();

        return view('favorites.index', [
            'recommendedProducts' => $recommendedProducts,
        ]);
    }

    public function items(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
        ]);

        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return response()->json([]);
        }

        $products = Product::query()
            ->whereIn('id', $ids)
            ->where('is_active', true)
            ->with('categoryGroup')
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => (float) $product->price,
                'formatted_price' => (float) $product->price <= 0 ? 'GRÁTIS' : 'R$ '.number_format((float) $product->price, 2, ',', '.'),
                'cover_image' => $product->cover_path ? asset($product->cover_path) : null,
                'category' => $product->categoryGroup?->name ?? $product->category ?? 'Sistema Web',
                'is_free' => (float) $product->price <= 0,
            ]);

        return response()->json($products);
    }
}
