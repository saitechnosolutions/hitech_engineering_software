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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->text('quotation_no')->nullable();
            $table->date('quotation_date')->nullable();
            $table->text('mode_terms_of_payment')->nullable();
            $table->text('buyer_reference_order_no')->nullable();
            $table->text('other_references')->nullable();
            $table->text('dispatch_through')->nullable();
            $table->text('destination')->nullable();
            $table->text('terms_of_delivery')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('number_of_rows')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};