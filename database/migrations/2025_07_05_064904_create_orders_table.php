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
        Schema::create('orders', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('branch_id')->nullable();
            $table->string('mobile', 15);
            $table->unsignedBigInteger('appointment_id');
            $table->string('service_name')->nullable();
            $table->string('service_duration')->nullable()->comment('Duration in minutes');
            $table->integer('service_qnty');
            $table->decimal('service_price', 8, 2)->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('product_price', 8, 2)->nullable();
            $table->string('product_qnty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
