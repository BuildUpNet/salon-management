<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = [
        'mobile',
        'branch_id',
        'appointment_id',
        'service_name',
        'service_price',
        'service_duration',
        'product_name',
        'product_price',
        'product_quantity',
        'created_at',
        'updated_at',
    ];
}
