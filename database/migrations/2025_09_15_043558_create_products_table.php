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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('product_name')->nullable();
            $table->string('product_image')->nullable();
            $table->string('brand')->nullable();
            $table->string('bike_model')->nullable();
            $table->decimal('mrp_price')->nullable();
            $table->string('part_number')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('variation')->nullable();
            $table->string('hsn_code')->nullable();
            $table->integer('stock_qty')->nullable();
            $table->text('design_sheet')->nullable();
            $table->text('data_sheet')->nullable();
            $table->timestamps();
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
