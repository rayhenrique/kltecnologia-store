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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('blog_category_id')->nullable()->after('category')->constrained('blog_categories')->nullOnDelete();
        });

        // Seed initial blog categories
        $defaultCategories = [
            [
                'name' => 'Atualizações',
                'description' => 'Novidades da plataforma, lançamentos de versões e comunicados da equipe KL Tecnologia.',
                'icon' => 'sparkles',
            ],
            [
                'name' => 'Tutoriais & Guias',
                'description' => 'Passo a passo prático, guias de instalação e instruções completas de configuração.',
                'icon' => 'book-open',
            ],
            [
                'name' => 'Tecnologia & Dev',
                'description' => 'Artigos sobre Laravel, PHP moderno, arquitetura de software e APIs.',
                'icon' => 'code',
            ],
            [
                'name' => 'E-commerce & Vendas',
                'description' => 'Estratégias de conversão, integrações de pagamento e gestão de infoprodutos.',
                'icon' => 'chart',
            ],
            [
                'name' => 'Dicas & Segurança',
                'description' => 'Boas práticas de proteção de arquivos digitais, servidores e performance web.',
                'icon' => 'shield-check',
            ],
        ];

        $now = now();

        foreach ($defaultCategories as $data) {
            $slug = Str::slug($data['name']);
            $id = DB::table('blog_categories')->insertGetId([
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'],
                'icon' => $data['icon'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Link existing posts that match this category string
            DB::table('posts')
                ->where('category', $data['name'])
                ->update(['blog_category_id' => $id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['blog_category_id']);
            $table->dropColumn('blog_category_id');
        });

        Schema::dropIfExists('blog_categories');
    }
};
