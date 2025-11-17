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
        Schema::create('quotation_batches', function (Blueprint $table) {
            $table->id();
            $table->date("batch_date")->nullable();
            $table->enum('priority', ['priority_1', 'priority_2', 'priority_3', 'priority_4', 'priority_5', 'retail'])->nullable();
            $table->json('quotation_ids')->nullable();
            $table->integer('quotation_count')->nullable();
            $table->enum('batch_status', ['processing', 'completed', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_batches');
    }
};