<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'tbl_employee';
    protected $primaryKey = 'emp_id';

    protected $fillable = [
        'emp_role',
        'emp_name',
        'emp_regid',
        'center_id',
        'emp_mobile',
        'emp_email',
        'emp_password',
        'emp_gender',
        'emp_type',
        'emp_pic',
        'del_status',
        'is_block',
        'created_by',
        'updated_by',
    ];


    public function assignedSessions()
    {
        return $this->hasMany(\App\Models\StudentSession::class, 'stu_id', 'stu_id');
    }

    public function assignedProgrammes()
    {
        return $this->hasMany(\App\Models\StudentProgramme::class, 'stu_id', 'stu_id');
    }

    public function assignedTechnologies()
    {
        return $this->hasMany(\App\Models\StudentTechnology::class, 'stu_id', 'stu_id');
    }

    public function assignedBatches()
    {
        return $this->hasMany(\App\Models\StudentBatch::class, 'stu_id', 'stu_id');
    }
}
