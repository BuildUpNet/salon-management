<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pending_bills', function (Blueprint $table) {
         $table->bigIncrements('id');
            $table->string('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('mobile')->nullable();
            $table->string('appointment_id')->nullable();
            $table->string('chair_id', 225)->collation('utf8mb4_bin');
            $table->string('staff_name')->nullable();
            $table->string('service_name')->nullable();
            $table->string('service_duration', 225)->nullable();
            $table->integer('service_qnty')->default(0);
            $table->decimal('service_price', 10, 2)->default(0.00);
            $table->string('product_name')->nullable();
            $table->string('product_qnty')->nullable();
            $table->decimal('product_price', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamps();
        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_bills');
    }
};
