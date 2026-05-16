<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'category',
        'price',
        'stock',
        'product_image' 
    ];
}
