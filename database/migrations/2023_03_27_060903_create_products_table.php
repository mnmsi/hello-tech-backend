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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('warranty_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('product_code')->unique();
            $table->double('price', 10, 2, true);
            $table->integer('discount_rate')->default(0);
            $table->tinyInteger('is_featured')->default(0)->comment('0: No, 1: Yes');
            $table->tinyInteger('is_active')->default(1)->comment('0: Inactive, 1: Active');
            $table->tinyInteger('is_official')->default(0)->comment('0: No, 1: Yes');
            $table->string('image_url');
            $table->string('hover_image_url')->nullable();
            $table->string('video_url')->nullable();
            $table->longText('description');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
