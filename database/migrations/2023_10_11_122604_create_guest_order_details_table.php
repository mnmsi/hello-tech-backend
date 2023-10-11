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
        Schema::create('guest_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_order_id')->constrained('guest_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_color_id')->constrained('product_colors')->onDelete('cascade');
            $table->string('feature')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->integer('discount_rate');
            $table->decimal('subtotal_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_order_details');
    }
};
