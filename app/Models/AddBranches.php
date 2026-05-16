<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddBranches extends Model
{
     use HasFactory;

    protected $table = 'add_branches'; // explicitly specify the correct table

    protected $fillable = [
        'branch_name',
        'branch_id',
        'email',
        'city',
        'state',
        'postal_code',
        'latitude',
        'longitude',
        'number_of_chairs',
        'logo',
    ];
    public function emp_details()
{
    return $this->hasMany(emp_detail::class, 'user_id', 'id');
}

public function chair_details()
{
    return $this->hasMany(chair_detail::class, 'branch_id', 'id');
}

public function bills()
{
    return $this->hasMany(Bill::class, 'branch_id', 'id');
}

}
