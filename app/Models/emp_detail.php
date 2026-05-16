<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class emp_detail extends Model
{
    use HasFactory;

    protected $table = 'emp_details';
    protected $fillable = [
        'user_id',
        'employee_id',
        'phone',
        'status',
    ];
    // In emp_detail.php model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(appointment::class, 'emp_id');
    }
    public function branch()
    {
        return $this->belongsTo(AddBranches::class, 'branch_id');
    }
}
