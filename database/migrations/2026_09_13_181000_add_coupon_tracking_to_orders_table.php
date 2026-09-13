<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->foreignId('coupon_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->timestamp('coupon_usage_counted_at')->nullable()->after('payment_method');
            $table->timestamp('coupon_usage_released_at')->nullable()->after('coupon_usage_counted_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn(['coupon_usage_counted_at', 'coupon_usage_released_at']);
        });
    }
};
