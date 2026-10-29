<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBatch extends Model
{
    protected $table = 'tbl_employee_batch';

    protected $fillable = [
        'emp_id',
        'batch_id',
        'created_by',
        'updated_by',
        'is_active',
    ];
}
