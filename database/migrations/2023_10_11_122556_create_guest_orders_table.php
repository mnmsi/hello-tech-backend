<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guest_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number', 20);
            $table->string('email')->nullable();
            $table->string('city');
            $table->string('division');
            $table->string('area');
            $table->string('delivery_option');
            $table->string('payment_method');
            $table->string('address_line')->nullable();
            $table->string('order_note')->nullable();
            $table->string('voucher_code')->nullable();
            $table->string('transaction_id');
            $table->string('order_key');
            $table->string('discount_rate')->default(0);
            $table->string('shipping_amount');
            $table->string('subtotal_price');
            $table->string('total_price');
            $table->enum('status', ['pending', 'processing', 'completed', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_orders');
    }
};
