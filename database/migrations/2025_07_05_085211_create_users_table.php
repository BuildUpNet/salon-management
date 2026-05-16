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
        Schema::create('users', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->string('name');
            $table->string('branch_id')->nullable();
            $table->string('mobile')->nullable()->index();
            $table->string('email')->index();
            $table->string('otp_email')->nullable();
            $table->string('otp', 6)->nullable();
            $table->dateTime('otp_expires_at')->nullable();
            $table->string('pending_otp_email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'emp', 'customer', 'branch']);
            $table->enum('role_type', ['Manager', 'Receptionist', 'Assistant'])->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
