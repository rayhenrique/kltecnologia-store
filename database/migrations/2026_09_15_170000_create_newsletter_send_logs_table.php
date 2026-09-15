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
        Schema::create('newsletter_send_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('notifiable_type')->nullable()->index();
            $table->unsignedBigInteger('notifiable_id')->nullable()->index();
            $table->timestamp('sent_at')->useCurrent()->index();
            $table->string('status', 20)->default('sent')->index();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id', 'email'], 'newsletter_log_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_send_logs');
    }
};
