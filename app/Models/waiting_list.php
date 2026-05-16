<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Customer;
use App\Models\chair_detail;
use App\Models\emp_detail;

use Illuminate\Database\Eloquent\Relations\HasMany;

class waiting_list extends Model
{
    use HasFactory;
    protected $table = 'waiting_lists';
    protected $fillable = ['customer_id','branch_id' ,'chair_id','staff_name', 'service_name','service_duration','service_qnty','service_price' , 'product_name' ,'product_qnty' , 'product_price','status' , 'cancle_status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function chair()
    {
        return $this->belongsTo(chair_detail::class, 'chair_id');
    }
    public function staff()
    {
        return $this->belongsTo(emp_detail::class, 'staff_name');
    }

    
}
