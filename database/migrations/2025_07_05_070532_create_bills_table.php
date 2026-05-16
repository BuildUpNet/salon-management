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
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch_id')->nullable();
            $table->unsignedBigInteger('appointment_id');
            $table->string('order_id', 225)->collation('utf8mb4_bin');
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->decimal('msf', 8, 2)->nullable(); 
            $table->decimal('final_amount', 10, 2);
            $table->enum('payment_type', ['cash', 'card', 'upi', 'wallet'])->default('cash');
            $table->string('status')->default('Paid');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
