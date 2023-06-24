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
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
            $table->foreign('brand_id')
                  ->references('id')
                  ->on('brands');
            $table->foreign('warranty_id')
                  ->references('id')
                  ->on('warranties');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_category_id_foreign');
            $table->dropForeign('products_brand_id_foreign');
            $table->dropForeign('products_warranty_id_foreign');
        });
    }
};
