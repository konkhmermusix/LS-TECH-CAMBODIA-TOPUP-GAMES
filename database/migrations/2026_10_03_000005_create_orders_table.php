<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique()->index();
            $table->foreignId('game_id')->constrained('games')->onDelete('restrict');
            $table->foreignId('game_package_id')->constrained('game_packages')->onDelete('restrict');
            $table->string('player_id', 100)->index();
            $table->string('zone_id', 50)->nullable()->index();
            $table->string('player_nickname', 100)->nullable();
            $table->string('customer_phone', 30)->nullable()->index();
            $table->string('customer_email', 100)->nullable()->index();
            $table->string('customer_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('package_name', 100);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 5)->default('KHR');
            $table->enum('status', [
                'pending_payment',
                'paid',
                'processing',
                'completed',
                'failed',
                'cancelled'
            ])->default('pending_payment')->index();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
