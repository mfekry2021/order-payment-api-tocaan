<?php

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->decimal('total',10)->default(0);
            $table->enum('payment_gateway', PaymentGateway::values());
            $table->enum('status', PaymentStatus::values())->default(PaymentStatus::PENDING->value);
            $table->string('transaction_id')->nullable();
            $table->json('callback_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
