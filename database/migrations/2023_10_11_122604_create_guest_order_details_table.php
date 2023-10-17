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
            $table->unsignedBigInteger("guest_order_id")->unsigned();
            $table->unsignedBigInteger("product_id")->unsigned();
            $table->unsignedBigInteger("product_color_id")->unsigned();
            $table->foreign('guest_order_id')->references('id')->on('guest_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_color_id')->references('id')->on('product_colors')->onDelete('cascade');
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
