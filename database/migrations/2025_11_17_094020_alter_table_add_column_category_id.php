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
        Schema::table('b_o_m_parts', function (Blueprint $table) {
            $table->unsignedBigInteger('production_bom_id')->nullable();
            $table->foreign('production_bom_id')->references('id')->on('product_components')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('b_o_m_parts', function (Blueprint $table) {
            //
        });
    }
};
