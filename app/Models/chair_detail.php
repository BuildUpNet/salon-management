<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chair_detail extends Model
{
    use HasFactory;
    protected $table = 'chair_details';
    protected $fillable = [ 'branch_id','chair_id', 'status'];

    public function branch()
    {
        return $this->belongsTo(AddBranches::class, 'branch_id');
    }
    public function appointments()
    {
        return $this->hasMany(appointment::class, 'chair_id');
    }
    public function waitingList()
    {
        return $this->hasMany(waiting_list::class, 'chair_id');
    }
    public function empDetail()
    {
        return $this->hasMany(emp_detail::class, 'chair_id');
    }
    public function pendingBills()
    {
        return $this->hasMany(pending_bill::class, 'chair_id');
    }
    
}
