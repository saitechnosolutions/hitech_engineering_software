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
        Schema::create('production_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quantity_production_id')->nullable();
            $table->foreign('quantity_production_id')->references('id')->on('quotation_production_stages')->onDelete('cascade');
            $table->date('entry_date')->nullable();
            $table->integer('completed_qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_histories');
    }
};