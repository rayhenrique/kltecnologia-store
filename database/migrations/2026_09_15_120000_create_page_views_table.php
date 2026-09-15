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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            $table->string('route_name', 100)->nullable()->index();
            $table->nullableMorphs('viewable');
            $table->string('visitor_hash', 64)->index();
            $table->string('referer', 500)->nullable();
            $table->string('device_type', 20)->default('desktop')->index();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            $table->index(['visited_at', 'visitor_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
