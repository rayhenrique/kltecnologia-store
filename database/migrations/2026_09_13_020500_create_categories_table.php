<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('slug')->constrained('categories')->nullOnDelete();
        });

        // Seed initial standard categories
        $defaultCategories = [
            [
                'name' => 'Scripts PHP',
                'description' => 'Scripts completos, códigos-fonte e soluções prontas em PHP puro e Laravel.',
                'icon' => 'code',
            ],
            [
                'name' => 'Sistemas SaaS',
                'description' => 'Aplicações web completas com gestão de assinaturas, multi-tenancy e painel administrativo.',
                'icon' => 'cloud',
            ],
            [
                'name' => 'Automação & Bots',
                'description' => 'Robôs, scrapers e fluxos de automação para WhatsApp, redes sociais e tarefas repetitivas.',
                'icon' => 'bot',
            ],
            [
                'name' => 'Marketing Digital',
                'description' => 'Ferramentas de captação de leads, landing pages de alta conversão e otimização de vendas.',
                'icon' => 'chart',
            ],
            [
                'name' => 'Templates & PLRs',
                'description' => 'Temas, layouts modernos e pacotes digitais com direito de revenda e edição.',
                'icon' => 'template',
            ],
            [
                'name' => 'Mobile & Aplicativos',
                'description' => 'Aplicativos híbridos e mobile para delivery, pedidos online e catálogos.',
                'icon' => 'mobile',
            ],
        ];

        foreach ($defaultCategories as $item) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'],
                'icon' => $item['icon'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Link existing products if matching
            DB::table('products')
                ->whereNull('category_id')
                ->where(function ($query) use ($item) {
                    $query->where('category', $item['name'])
                        ->orWhere('category', 'like', "%{$item['name']}%");
                })
                ->update(['category_id' => $categoryId]);
        }

        // Catch any remaining products with a custom category string
        $distinctCategories = DB::table('products')
            ->whereNull('category_id')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        foreach ($distinctCategories as $catName) {
            $slug = Str::slug($catName);
            if (! DB::table('categories')->where('slug', $slug)->exists()) {
                $newId = DB::table('categories')->insertGetId([
                    'name' => $catName,
                    'slug' => $slug,
                    'description' => "Produtos da categoria {$catName}.",
                    'icon' => 'folder',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('products')->where('category', $catName)->update(['category_id' => $newId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
