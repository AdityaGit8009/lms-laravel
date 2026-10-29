<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgramme extends Model
{
  protected $table = 'tbl_student_programme';

  protected $fillable = [
    'stu_id',
    'programme_id',
    'created_by',
    'updated_by',
  ];

  public function student()
  {
    return $this->belongsTo(Student::class, 'stu_id', 'stu_id');
  }

  public function programme()
  {
    return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
  }
}
