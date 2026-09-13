<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductStorageService $storage) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Product::class);

        $search = trim((string) ($request->query('q') ?? $request->query('search', '')));
        $status = trim((string) $request->query('status', 'all'));
        $featured = trim((string) $request->query('featured', 'all'));

        $query = Product::query()->with('categoryGroup');

        if ($search !== '') {
            $query->search($search);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($featured === '1' || $featured === 'featured') {
            $query->where('is_featured', true);
        }

        $metrics = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'featured' => Product::where('is_featured', true)->count(),
        ];

        $products = $query->latest('id')->paginate(10)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
            'currentStatus' => $status,
            'currentFeatured' => $featured,
            'metrics' => $metrics,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Product::class);

        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->storage->create($request->validated(), $request->file('cover'), $request->file('file'));

        return redirect()->route('admin.products.index')->with('success', 'Produto criado com sucesso.');
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->storage->update($product, $request->validated(), $request->file('cover'), $request->file('file'));

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);
        $this->storage->archive($product);

        return redirect()->route('admin.products.index')->with('success', 'Produto arquivado com sucesso.');
    }
}
