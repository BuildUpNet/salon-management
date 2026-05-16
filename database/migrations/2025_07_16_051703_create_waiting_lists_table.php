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
        Schema::create('waiting_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('chair_id')->nullable()->collation('utf8mb4_bin');
            $table->string('staff_name')->nullable();
            $table->string('service_name')->nullable();
            $table->string('service_duration')->nullable();
            $table->integer('service_qnty')->nullable();
            $table->decimal('service_price', 10, 0)->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_qnty')->nullable();
            $table->decimal('product_price', 10, 0)->nullable();
            $table->enum('status', ['waiting', 'assigned'])->default('waiting');
            $table->tinyInteger('waiting_status')->default(0);
            $table->tinyInteger('cancle_status')->default(0);
            $table->timestamps(); 
            // Foreign key constraint
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiting_lists');
    }
};

