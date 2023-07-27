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
        Schema::create('dynamic_page_brand', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_page_id')
                ->references('id')
                ->on("dynamic_page")
                ->cascadeOnDelete();
            $table->integer("brand_id");
            $table->integer("product_count")->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_page_brand');
    }
};
