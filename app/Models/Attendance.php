<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'emp_id',
        'date',
        'staff_name',
        'role',
        'check_in',
        'check_out',
        'hours',
        'final_status',
        'remarks'
    ];

    protected $dates = ['check_in', 'check_out'];
public function user()
{
    return $this->belongsTo(User::class);
}

}


