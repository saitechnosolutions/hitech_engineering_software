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
        Schema::table('quotation_products', function (Blueprint $table) {
            $table->integer('available_stock')->nullable()->comment('Stock get in Product Store');
            $table->integer('production_stock')->nullable()->comment('Move to product in production');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_products', function (Blueprint $table) {
            //
        });
    }
};
