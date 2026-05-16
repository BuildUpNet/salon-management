<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_service extends Model
{
    use HasFactory;
    protected $fillable = ['service_id', 'service_name', 'gender', 'duration', 'price'];

    public function service()
    {
        return $this->belongsTo(add_service::class, 'service_id');
    }
    public function branch()
    {
        return $this->belongsTo(AddBranches::class, 'branch_id');
    }
}
