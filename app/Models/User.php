<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\emp_detail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'branch_id', // Added branch_id to the fillable attributes
        'email',
        'mobile',
        'password',
        'role',
        'role_type',
        'otp_email',
        'otp',
        'otp_expires_at',
        'pending_otp_email',
        'status' // Added occupied_status to the fillable attributes
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

public function empDetail()
{
    return $this->hasOne(\App\Models\emp_detail::class, 'user_id');
}


}
