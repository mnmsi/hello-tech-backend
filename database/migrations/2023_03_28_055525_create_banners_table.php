<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new  class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->enum('page', ['home', 'new-arrivals', 'product-detail'])->nullable();
            $table->enum('show_on', ['top', 'bottom', 'all', 'detail'])->nullable();
            $table->integer('order_no')->nullable();
            $table->string('image_url')->nullable();
            $table->longText("home_images")->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('0: Inactive, 1: Active');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
