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
        Schema::table('products', function (Blueprint $table): void {
            $table->text('short_description')->nullable()->after('description');
            $table->string('seo_title', 255)->nullable()->after('short_description');
            $table->text('meta_description')->nullable()->after('seo_title');
            $table->string('product_type', 100)->nullable()->after('meta_description');
            $table->string('brand', 150)->nullable()->after('product_type');
            $table->text('features')->nullable()->after('brand');
            $table->text('requirements')->nullable()->after('features');
            $table->text('license')->nullable()->after('requirements');
            $table->text('support_info')->nullable()->after('license');
            $table->string('demo_url', 500)->nullable()->after('support_info');
            $table->string('documentation_url', 500)->nullable()->after('demo_url');
            $table->boolean('includes_source_code')->default(false)->after('documentation_url');
            $table->boolean('lifetime_access')->default(false)->after('includes_source_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn([
                'short_description',
                'seo_title',
                'meta_description',
                'product_type',
                'brand',
                'features',
                'requirements',
                'license',
                'support_info',
                'demo_url',
                'documentation_url',
                'includes_source_code',
                'lifetime_access',
            ]);
        });
    }
};
