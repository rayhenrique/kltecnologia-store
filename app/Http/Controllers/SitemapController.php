<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // 1. Páginas Institucionais e Principais
        $latestProduct = Product::query()->availableForSale()->latest('updated_at')->first();
        $latestPost = Post::query()->where('is_published', true)->latest('updated_at')->first();

        $urls[] = [
            'loc' => route('storefront.index'),
            'lastmod' => $latestProduct?->updated_at?->toAtomString() ?? now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        $urls[] = [
            'loc' => route('catalog.index'),
            'lastmod' => $latestProduct?->updated_at?->toAtomString() ?? now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];

        $urls[] = [
            'loc' => route('blog.index'),
            'lastmod' => $latestPost?->updated_at?->toAtomString() ?? now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];

        $urls[] = [
            'loc' => route('privacy.index'),
            'lastmod' => now()->startOfMonth()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.3',
        ];

        $urls[] = [
            'loc' => route('terms.index'),
            'lastmod' => now()->startOfMonth()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.3',
        ];

        // 2. Categorias de Produtos Ativas
        $categories = Category::query()
            ->where('is_active', true)
            ->has('products')
            ->orderBy('name')
            ->get();

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('catalog.index', ['categoria' => $category->slug]),
                'lastmod' => $category->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // 3. Produtos Ativos e Disponíveis para Venda
        $products = Product::query()
            ->availableForSale()
            ->orderByDesc('updated_at')
            ->get(['id', 'slug', 'updated_at']);

        foreach ($products as $product) {
            $urls[] = [
                'loc' => route('storefront.show', $product->slug),
                'lastmod' => $product->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ];
        }

        // 4. Categorias de Blog Ativas
        $blogCategories = BlogCategory::query()
            ->where('is_active', true)
            ->has('posts')
            ->orderBy('name')
            ->get();

        foreach ($blogCategories as $blogCategory) {
            $urls[] = [
                'loc' => route('blog.index', ['categoria' => $blogCategory->slug]),
                'lastmod' => $blogCategory->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // 5. Artigos Publicados do Blog
        $posts = Post::query()
            ->where('is_published', true)
            ->orderByDesc('updated_at')
            ->get(['id', 'slug', 'updated_at']);

        foreach ($posts as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
