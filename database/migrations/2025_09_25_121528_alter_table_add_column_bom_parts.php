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
            $table->text('drawing_no')->nullable();
            $table->text('bom_code')->nullable();
            $table->enum('bom_type', ['production', 'packing'])->nullable();
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
