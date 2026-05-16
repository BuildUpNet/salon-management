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
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
    $table->string('branch_id');
    $table->string('emp_id');
    $table->date('date');
    $table->string('staff_name');
    $table->string('role')->nullable();
    $table->time('check_in')->nullable();
    $table->time('check_out')->nullable();
    $table->string('hours')->nullable();
    $table->string('remarks')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
