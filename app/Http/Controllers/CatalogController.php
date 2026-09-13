<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogFilterRequest;
use App\Models\Product;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(CatalogFilterRequest $request): View
    {
        $validated = $request->validated();
        $query = Product::query()->where('is_active', true);

        if (! empty($validated['q'])) {
            $search = trim((string) $validated['q']);
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($validated['category']) && $validated['category'] !== 'all') {
            $category = trim((string) $validated['category']);
            $query->where(function ($q) use ($category): void {
                $q->where('title', 'like', "%{$category}%")
                    ->orWhere('description', 'like', "%{$category}%");
            });
        }

        if (isset($validated['min_price']) && is_numeric($validated['min_price'])) {
            $query->where('price', '>=', (float) $validated['min_price']);
        }

        if (isset($validated['max_price']) && is_numeric($validated['max_price'])) {
            $query->where('price', '<=', (float) $validated['max_price']);
        }

        $sort = $validated['sort'] ?? 'latest';
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'title_asc' => $query->orderBy('title', 'asc'),
            default => $query->orderByDesc('updated_at')->orderByDesc('created_at'),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'filters' => $validated,
            'currentSort' => $sort,
        ]);
    }
}
