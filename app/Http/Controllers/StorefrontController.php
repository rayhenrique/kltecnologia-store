<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListFilterRequest;
use App\Models\Product;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(ListFilterRequest $request): View
    {
        $search = trim((string) $request->validated('q', ''));

        $query = Product::query()->availableForSale();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Catálogo Completo: sempre priorizar produtos criados ou atualizados recentemente
        $products = (clone $query)
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        // Produtos em Destaque: produtos selecionados no painel admin
        $featuredProducts = (clone $query)
            ->where('is_featured', true)
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // Fallback para manter o layout preenchido caso o admin ainda não tenha marcado nenhum item
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = (clone $query)
                ->orderByDesc('updated_at')
                ->orderByDesc('created_at')
                ->take(4)
                ->get();
        }

        $recentUpdates = (clone $query)
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

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

        abort_unless($product->file_path, 404);

        $relatedProducts = Product::query()
            ->availableForSale()
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('storefront.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
