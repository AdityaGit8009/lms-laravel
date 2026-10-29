<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProgramme extends Model
{
    protected $table = 'tbl_employee_programme';

    protected $fillable = [
        'emp_id',
        'programme_id',
        'created_by',
        'updated_by',
        'is_active',
    ];
}
