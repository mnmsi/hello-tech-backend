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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('delivery_option_id');
            $table->unsignedBigInteger('user_address_id')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('city');
            $table->string('division');
            $table->string('area');
            $table->string('address_line')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('order_key')->nullable();
            $table->integer('discount_rate')->nullable();
            $table->integer('shipping_amount')->nullable();
            $table->decimal('subtotal_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->longText('order_note')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'delivered', 'cancelled'])->default('pending');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
