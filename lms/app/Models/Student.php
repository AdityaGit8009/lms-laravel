<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  protected $table = 'tbl_student';
  protected $primaryKey = 'stu_id';

  protected $fillable = [
    'stu_role',
    'stu_name',
    'stu_regid',
    'stu_college',
    'stu_course',
    'stu_course_year',
    'stu_branch',
    'stu_mobile',
    'stu_email',
    'stu_password',
    'stu_gender',
    'stu_pic',
    'stu_pro',
    'center_id',
    'stu_session',
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
