<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogFilterRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(CatalogFilterRequest $request): View|RedirectResponse
    {
        $validated = $request->validated();
        $categoryInput = ! empty($validated['category']) ? $validated['category'] : (! empty($validated['categoria']) ? $validated['categoria'] : null);

        if (! empty($categoryInput) && $categoryInput !== 'all') {
            $catSlug = trim((string) $categoryInput);
            $matched = Category::where('slug', $catSlug)->orWhere('name', $catSlug)->first();

            // Redirect 301 if it's a direct clean category request without other search parameters
            if ($matched && empty($validated['q']) && empty($validated['min_price']) && empty($validated['max_price']) && empty($validated['sort'])) {
                return redirect()->route('catalog.category', $matched->slug, 301);
            }
        }

        $query = Product::query()->availableForSale();

        if (! empty($validated['q'])) {
            $search = trim((string) $validated['q']);
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (! empty($categoryInput) && $categoryInput !== 'all') {
            $categoryParam = trim((string) $categoryInput);
            $matched = Category::where('slug', $categoryParam)->orWhere('name', $categoryParam)->first();

            if ($matched) {
                $query->where('category_id', $matched->id);
            } else {
                $query->where(function ($q) use ($categoryParam): void {
                    $q->where('category', $categoryParam)
                        ->orWhere('title', 'like', "%{$categoryParam}%");
                });
            }
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

        $allCategories = Category::query()
            ->active()
            ->withCount(['products' => fn ($q) => $q->availableForSale()])
            ->orderBy('name')
            ->get();

        return view('catalog.index', [
            'products' => $products,
            'filters' => $validated,
            'currentSort' => $sort,
            'currentCategory' => null,
            'allCategories' => $allCategories,
        ]);
    }

    public function category(Category $category, CatalogFilterRequest $request): View
    {
        abort_unless($category->is_active, 404);

        $validated = $request->validated();
        $query = Product::query()->availableForSale()->where('category_id', $category->id);

        if (! empty($validated['q'])) {
            $search = trim((string) $validated['q']);
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
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

        $allCategories = Category::query()
            ->active()
            ->withCount(['products' => fn ($q) => $q->availableForSale()])
            ->orderBy('name')
            ->get();

        return view('catalog.index', [
            'products' => $products,
            'filters' => $validated,
            'currentSort' => $sort,
            'currentCategory' => $category,
            'allCategories' => $allCategories,
        ]);
    }
}
