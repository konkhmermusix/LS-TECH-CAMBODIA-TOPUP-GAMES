<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('provider_code', 100);
            $table->unsignedInteger('amount_value');
            $table->unsignedInteger('bonus_value')->default(0);
            $table->decimal('price_khr', 12, 2);
            $table->decimal('price_usd', 10, 2);
            $table->decimal('original_price_khr', 12, 2)->nullable();
            $table->string('badge', 50)->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_packages');
    }
};
