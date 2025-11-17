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
        Schema::table('quotations', function (Blueprint $table) {
            $table->integer('cgst_percentage')->nullable();
            $table->decimal('cgst_value')->nullable();
            $table->integer('sgst_percentage')->nullable();
            $table->decimal('sgst_value')->nullable();
            $table->integer('igst_percentage')->nullable();
            $table->decimal('igst_value')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            //
        });
    }
};
