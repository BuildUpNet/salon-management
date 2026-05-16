<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    protected $table = 'customers'; // Specify the table name if it's not the plural form of the model name
    protected $fillable = [
        'name',
        'branch_id', 
        'mobile',
        'email',
        'gender',
        'senior_citizen',
    ];
}
