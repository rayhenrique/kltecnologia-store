<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListFilterRequest;
use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(ListFilterRequest $request): View|RedirectResponse
    {
        $search = trim((string) $request->validated('q', ''));
        $category = trim((string) $request->validated('categoria', ''));

        // Redirect 301 from legacy query string ?categoria=slug to clean SEO route /blog/categoria/{slug}
        if ($category !== '' && $search === '') {
            $blogCat = BlogCategory::where('slug', $category)->orWhere('name', $category)->first();
            if ($blogCat) {
                return redirect()->route('blog.category', $blogCat->slug, 301);
            }
        }

        $query = Post::query()->published();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $blogCat = BlogCategory::where('slug', $category)->orWhere('name', $category)->first();
            if ($blogCat) {
                $query->where('blog_category_id', $blogCat->id);
            } else {
                $query->where('category', $category);
            }
        }

        $posts = $query->latest('published_at')->latest('id')->paginate(9)->withQueryString();

        $categories = BlogCategory::query()
            ->active()
            ->whereHas('posts', fn ($q) => $q->published())
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->get();

        $recentPosts = Post::query()
            ->published()
            ->latest('published_at')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
            'search' => $search,
            'selectedCategory' => $category,
            'currentBlogCategory' => null,
        ]);
    }

    public function category(BlogCategory $blogCategory, ListFilterRequest $request): View
    {
        abort_unless($blogCategory->is_active, 404);

        $search = trim((string) $request->validated('q', ''));

        $query = Post::query()->published()->where('blog_category_id', $blogCategory->id);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->latest('id')->paginate(9)->withQueryString();

        $categories = BlogCategory::query()
            ->active()
            ->whereHas('posts', fn ($q) => $q->published())
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->get();

        $recentPosts = Post::query()
            ->published()
            ->latest('published_at')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
            'search' => $search,
            'selectedCategory' => $blogCategory->name,
            'currentBlogCategory' => $blogCategory,
        ]);
    }

    public function show(Request $request, Post $post): View
    {
        if (! $post->is_published && ! ($request->user()?->isAdmin())) {
            abort(404);
        }

        // Increment view count in session to avoid abuse on simple refreshes
        $viewedKey = 'viewed_post_'.$post->id;
        if (! session()->has($viewedKey)) {
            $post->increment('views_count');
            session()->put($viewedKey, true);
        }

        $relatedPosts = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->blog_category_id, function ($query) use ($post): void {
                $query->where('blog_category_id', $post->blog_category_id);
            }, function ($query) use ($post): void {
                if ($post->category) {
                    $query->where('category', $post->category);
                }
            })
            ->latest('published_at')
            ->limit(3)
            ->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = Post::query()
                ->published()
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->limit(3)
                ->get();
        }

        $recentPosts = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'recentPosts' => $recentPosts,
        ]);
    }
}
