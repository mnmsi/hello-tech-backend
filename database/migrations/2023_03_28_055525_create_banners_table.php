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
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->enum('page', ['home', 'new-arrivals', 'brands', 'product-detail', 'all-bikes', 'popular-brands', 'bike-accessories', 'our-showrooms']);
            $table->enum('show_on', ['top', 'bottom', 'all', 'detail']);
            $table->string('image_url');
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
