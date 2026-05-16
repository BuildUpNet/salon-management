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
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch_id', 225)->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->string('mobile', 15);
            $table->date('date');
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->string('chair_id', 225)->nullable()->collation('utf8mb4_bin');
            $table->unsignedBigInteger('staff_id')->nullable();
             $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
