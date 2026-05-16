<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'branch_id',
        'mobile',
        'date',
        'chair_id',
        'staff_id',
       'time_in',
        'time_out',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(customer::class);
    }

    public function chair()
    {
        return $this->belongsTo(chair_detail::class, 'chair_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function branch()
    {
        return $this->belongsTo(AddBranches::class, 'branch_id');
    }
}
