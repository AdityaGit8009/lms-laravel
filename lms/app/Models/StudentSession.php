<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSession extends Model
{
  protected $table = 'tbl_student_session';

  protected $fillable = [
    'stu_id',
    'session_id',
    'created_by',
    'updated_by',
  ];

  public function student()
  {
    return $this->belongsTo(Student::class, 'stu_id', 'stu_id');
  }

  public function session()
  {
    return $this->belongsTo(Session::class, 'session_id', 'session_id');
  }
}
