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
        Schema::table('production_histories', function (Blueprint $table) {
            $table->enum('delivery_challan_status', ['pending', 'created', 'completed'])->default('pending');
            $table->unsignedBigInteger('delivery_challan_id')->nullable();
            $table->foreign('delivery_challan_id')->references('id')->on('delivery_challans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_histories', function (Blueprint $table) {
            //
        });
    }
};
