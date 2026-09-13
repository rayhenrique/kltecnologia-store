<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', BlogCategory::class);

        $search = trim((string) $request->query('q', ''));

        $query = BlogCategory::query()->withCount('posts');

        if ($search !== '') {
            $query->search($search);
        }

        $categories = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.blog_categories.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', BlogCategory::class);

        return view('admin.blog_categories.create');
    }

    public function store(StoreBlogCategoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', BlogCategory::class);

        BlogCategory::create($request->validated());

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Categoria do blog cadastrada com sucesso.');
    }

    public function edit(BlogCategory $blogCategory): View
    {
        Gate::authorize('update', $blogCategory);

        $blogCategory->loadCount('posts');

        return view('admin.blog_categories.edit', [
            'blogCategory' => $blogCategory,
        ]);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory): RedirectResponse
    {
        Gate::authorize('update', $blogCategory);

        $blogCategory->update($request->validated());

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Categoria do blog atualizada com sucesso.');
    }

    public function destroy(BlogCategory $blogCategory): RedirectResponse
    {
        Gate::authorize('delete', $blogCategory);

        $blogCategory->posts()->update(['blog_category_id' => null]);
        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Categoria do blog removida com sucesso.');
    }
}
