<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->where(function ($query): void {
                $query->whereNull('file_path')->orWhere('file_path', '');
            })
            ->update([
                'is_active' => false,
                'is_featured' => false,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Produtos sem arquivo não devem ser reativados automaticamente em rollback.
    }
};
