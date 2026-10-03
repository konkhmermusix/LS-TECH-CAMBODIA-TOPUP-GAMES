<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('transaction_id', 100)->unique()->index();
            $table->string('gateway_transaction_id', 150)->nullable()->index();
            $table->string('payment_method', 50)->default('aba_khqr');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 5)->default('KHR');
            $table->longText('qr_string')->nullable();
            $table->longText('qr_image_url')->nullable();
            $table->text('deep_link')->nullable();
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired'
            ])->default('pending')->index();
            $table->text('failure_reason')->nullable();
            $table->json('raw_payload')->nullable();
            $table->json('raw_callback')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
