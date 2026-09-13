<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::query()
            ->availableForSale()
            ->where('is_featured', true)
            ->with('categoryGroup')
            ->orderByDesc('updated_at')
            ->take(4)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::query()
                ->availableForSale()
                ->with('categoryGroup')
                ->orderByDesc('updated_at')
                ->take(4)
                ->get();
        }

        return view('cart.index', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
