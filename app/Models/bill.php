<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bill extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'appointment_id',
        'order_id',
       'discount',
        'total', 
        'msf',
        'final_amount',
        'payment_type',
    ];
     public function branch()
    {
        return $this->belongsTo(AddBranches::class, 'branch_id', 'id');
    }
}
