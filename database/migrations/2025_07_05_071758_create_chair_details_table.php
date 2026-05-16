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
        Schema::create('chair_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch_id')->nullable()->collation('utf8mb4_bin');
            $table->string('chair_id');
            $table->tinyInteger('status')->default(0)->comment('0 = not booked, 1 = booked');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chair_details');
    }
};
