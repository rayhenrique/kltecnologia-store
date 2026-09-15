<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Requests\ListFilterRequest;
use App\Models\Product;
use App\Services\NewsletterBroadcastService;
use App\Services\ProductStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductStorageService $storage,
        private readonly NewsletterBroadcastService $newsletterBroadcast,
    ) {}

    public function index(ListFilterRequest $request): View
    {
        Gate::authorize('viewAny', Product::class);

        $search = trim((string) ($request->validated('q') ?? $request->validated('search', '')));
        $status = trim((string) $request->validated('status', 'all'));
        $featured = trim((string) $request->validated('featured', 'all'));

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

    public function store(StoreProductRequest $request): RedirectResponse|JsonResponse
    {
        $product = $this->storage->create($request->validated(), $request->file('cover'), $request->file('file'));

        if ($product->is_active) {
            $this->newsletterBroadcast->broadcastNewProduct($product);
        }

        if ($request->wantsJson()) {
            session()->flash('success', 'Produto criado com sucesso.');

            return response()->json([
                'success' => true,
                'message' => 'Produto criado com sucesso.',
                'redirect' => route('admin.products.index'),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produto criado com sucesso.');
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse|JsonResponse
    {
        $wasActive = (bool) $product->is_active;

        $updatedProduct = $this->storage->update($product, $request->validated(), $request->file('cover'), $request->file('file'));

        if (! $wasActive && $updatedProduct->is_active) {
            $this->newsletterBroadcast->broadcastNewProduct($updatedProduct);
        }

        if ($request->wantsJson()) {
            session()->flash('success', 'Produto atualizado com sucesso.');

            return response()->json([
                'success' => true,
                'message' => 'Produto atualizado com sucesso.',
                'redirect' => route('admin.products.index'),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);
        $this->storage->archive($product);

        return redirect()->route('admin.products.index')->with('success', 'Produto arquivado com sucesso.');
    }
}
