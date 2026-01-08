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
        Schema::table('stock_inward_outward_details', function (Blueprint $table) {
            $table->enum('stock_status', ['stock_in', 'stock_out'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_inward_outward_details', function (Blueprint $table) {
            //
        });
    }
};