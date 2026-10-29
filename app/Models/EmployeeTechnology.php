<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTechnology extends Model
{
    protected $table = 'tbl_employee_technology';

    protected $fillable = [
        'emp_id',
        'technology_id',
        'created_by',
        'updated_by',
        'is_active',
    ];
}
