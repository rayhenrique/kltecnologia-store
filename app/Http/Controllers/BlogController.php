<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('categoria', ''));

        $query = Post::query()->published();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        $posts = $query->latest('published_at')->latest('id')->paginate(9)->withQueryString();

        $categories = Post::query()
            ->published()
            ->whereNotNull('category')
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
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
            ->when($post->category, function ($query, $category): void {
                $query->where('category', $category);
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
