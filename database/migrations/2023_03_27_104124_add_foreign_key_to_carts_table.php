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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->foreign('product_color_id')
                  ->references('id')
                  ->on('product_colors');
            $table->foreign('product_data_id')
                  ->references('id')
                  ->on('product_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_product_id_foreign');
            $table->dropForeign('carts_product_color_id_foreign');
            $table->dropForeign('carts_product_data_id_foreign');
        });
    }
};
