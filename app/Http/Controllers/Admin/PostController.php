<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Post::class);

        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('categoria', ''));

        $query = Post::query();

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

        return view('admin.posts.create');
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validated();
        $coverPath = $request->hasFile('cover') ? $this->storeCover($request->file('cover'), $validated['title']) : null;

        Post::create([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? 'Geral',
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'cover_path' => $coverPath,
            'is_published' => $validated['is_published'] ?? true,
            'published_at' => ($validated['is_published'] ?? true) ? now() : null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Artigo publicado com sucesso.');
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            $this->deleteCover($post->cover_path);
            $post->cover_path = $this->storeCover($request->file('cover'), $validated['title']);
        }

        $post->title = $validated['title'];
        $post->category = $validated['category'] ?? 'Geral';
        $post->excerpt = $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 180);
        $post->content = $validated['content'];
        $post->is_published = $validated['is_published'] ?? true;

        if ($post->is_published && ! $post->published_at) {
            $post->published_at = now();
        }

        $post->save();

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
