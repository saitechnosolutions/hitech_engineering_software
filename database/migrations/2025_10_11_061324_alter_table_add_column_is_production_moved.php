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
            $table->enum('is_production_moved', ['Yes', 'No'])->default('No');
            $table->date('batch_date')->nullable();
            $table->text('priority')->nullable();
            $table->enum('production_status', ['not_moved', 'production_moved', 'ongoing', 'completed'])->default('not_moved');
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