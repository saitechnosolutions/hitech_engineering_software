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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->text('task_details')->nullable();
            $table->date('task_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->enum('task_type', ['single_time', 'repeating_task']);
            $table->date('repeating_task_date')->nullable();
            $table->enum('priority', ['High', 'Low', 'Medium'])->nullable();
            $table->text('create_task_image')->nullable();
            $table->text('comple_task_image')->nullable();
            $table->timestamp('completed_task_timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
