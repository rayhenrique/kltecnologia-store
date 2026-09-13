<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $query = Product::query()->where('is_active', true);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = (clone $query)->latest()->paginate(12)->withQueryString();
        $featuredProducts = (clone $query)->latest()->take(4)->get();
        $recentUpdates = (clone $query)->latest()->take(8)->get();

        return view('storefront.index', [
            'products' => $products,
            'featuredProducts' => $featuredProducts,
            'recentUpdates' => $recentUpdates,
            'search' => $search,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('storefront.show', compact('product'));
    }
}
