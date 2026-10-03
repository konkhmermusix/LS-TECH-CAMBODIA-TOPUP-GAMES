<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topup_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('provider', 50)->index();
            $table->string('reference_id', 100)->unique()->index();
            $table->string('provider_order_id', 150)->nullable()->index();
            $table->string('player_id', 100);
            $table->string('zone_id', 50)->nullable();
            $table->string('package_code', 100);
            $table->enum('status', [
                'pending',
                'processing',
                'success',
                'failed'
            ])->default('pending')->index();
            $table->string('error_code', 100)->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topup_transactions');
    }
};
