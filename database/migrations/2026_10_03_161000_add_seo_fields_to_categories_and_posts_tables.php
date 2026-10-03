<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->string('seo_title', 255)->nullable()->after('description');
            $table->text('meta_description')->nullable()->after('seo_title');
        });

        Schema::table('blog_categories', function (Blueprint $table): void {
            $table->string('seo_title', 255)->nullable()->after('description');
            $table->text('meta_description')->nullable()->after('seo_title');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->string('seo_title', 255)->nullable()->after('excerpt');
            $table->text('meta_description')->nullable()->after('seo_title');
        });

        Schema::create('slug_redirects', function (Blueprint $table): void {
            $table->id();
            $table->string('model_type', 50)->index();
            $table->string('old_slug', 255)->index();
            $table->string('target_slug', 255);
            $table->timestamps();

            $table->unique(['model_type', 'old_slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slug_redirects');

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'meta_description']);
        });

        Schema::table('blog_categories', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'meta_description']);
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'meta_description']);
        });
    }
};
