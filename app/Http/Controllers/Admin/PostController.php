<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Http\Requests\ListFilterRequest;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Services\HtmlSanitizerService;
use App\Services\NewsletterBroadcastService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly HtmlSanitizerService $sanitizer,
        private readonly NewsletterBroadcastService $newsletterBroadcast,
    ) {}

    public function index(ListFilterRequest $request): View
    {
        Gate::authorize('viewAny', Post::class);

        $search = trim((string) $request->validated('q', ''));
        $category = trim((string) $request->validated('categoria', ''));

        $query = Post::query()->with('blogCategory');

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where(function ($q) use ($category): void {
                $q->where('category', $category)
                    ->orWhereHas('blogCategory', function ($bq) use ($category): void {
                        $bq->where('name', $category)->orWhere('slug', $category);
                    });
            });
        }

        $posts = $query->latest()->paginate(12)->withQueryString();
        $categories = Post::query()->whereNotNull('category')->distinct()->pluck('category');

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $category,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Post::class);

        $blogCategories = BlogCategory::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.posts.create', [
            'blogCategories' => $blogCategories,
        ]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validated();
        $coverPath = $request->hasFile('cover') ? $this->storeCover($request->file('cover'), $validated['title']) : null;

        $blogCategory = ! empty($validated['blog_category_id'])
            ? BlogCategory::find($validated['blog_category_id'])
            : null;

        $categoryName = $blogCategory?->name ?? ($validated['category'] ?? 'Geral');

        $content = $this->sanitizer->sanitize($validated['content']);

        $isPublished = (bool) ($validated['is_published'] ?? true);

        $post = Post::create([
            'title' => $validated['title'],
            'category' => $categoryName,
            'blog_category_id' => $blogCategory?->id,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($content), 180),
            'content' => $content,
            'cover_path' => $coverPath,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        if ($post->is_published) {
            $this->newsletterBroadcast->broadcastNewPost($post);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Artigo publicado com sucesso.');
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        $blogCategories = BlogCategory::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.posts.edit', [
            'post' => $post,
            'blogCategories' => $blogCategories,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $wasPublished = (bool) $post->is_published;

        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            $this->deleteCover($post->cover_path);
            $post->cover_path = $this->storeCover($request->file('cover'), $validated['title']);
        }

        $blogCategory = ! empty($validated['blog_category_id'])
            ? BlogCategory::find($validated['blog_category_id'])
            : null;

        $categoryName = $blogCategory?->name ?? ($validated['category'] ?? $post->category ?? 'Geral');

        $post->title = $validated['title'];
        $post->category = $categoryName;
        $post->blog_category_id = $blogCategory?->id ?? $post->blog_category_id;
        $content = $this->sanitizer->sanitize($validated['content']);
        $post->excerpt = $validated['excerpt'] ?? Str::limit(strip_tags($content), 180);
        $post->content = $content;
        $post->is_published = (bool) ($validated['is_published'] ?? true);

        if ($post->is_published && ! $post->published_at) {
            $post->published_at = now();
        }

        $post->save();

        if (! $wasPublished && $post->is_published) {
            $this->newsletterBroadcast->broadcastNewPost($post);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Artigo atualizado com sucesso.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $this->deleteCover($post->cover_path);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Artigo excluído com sucesso.');
    }

    private function storeCover(UploadedFile $file, string $title): string
    {
        $directory = public_path('blog_covers');
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $slug = Str::slug($title);
        $filename = "{$slug}-".uniqid().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return "blog_covers/{$filename}";
    }

    private function deleteCover(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
