<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSession extends Model
{
    protected $table = 'tbl_employee_session';

    protected $fillable = [
        'emp_id',
        'session_id',
        'created_by',
        'updated_by',
        'is_active',
    ];
}
