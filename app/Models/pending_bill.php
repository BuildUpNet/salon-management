<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pending_bill extends Model
{
   
    use HasFactory;
    protected $table = 'pending_bills';
    protected $fillable = [
        'branch_id',
        'customer_id',
        'appointment_id',
        'chair_id',
        'staff_name',
        'service_name',
        'service_duration',
        'service_qnty',
        'service_price',
        'product_name',
        'product_qnty',
        'product_price',
        'total_amount',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(customer::class);
    }
    public function appointment()
    {
        return $this->belongsTo(appointment::class);
    }
    public function chair()
    {
        return $this->belongsTo(chair_detail::class);
    }
    public function staff(){
        return $this->belongsTo(emp_detail::class);
    }

}
