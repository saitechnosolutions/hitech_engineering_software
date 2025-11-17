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
        Schema::create('invoice_request_products', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('invoice_request_id')->nullable();
            $table->foreign('invoice_request_id')->references('id')->on('invoice_requests')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->decimal('rate')->nullable();
            $table->text('uom')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_request_products');
    }
};