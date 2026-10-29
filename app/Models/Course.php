<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
  protected $table = 'tbl_courses';
  protected $primaryKey = 'course_id';

  protected $fillable = [
    'course_name',
    'duration',
    'created_by',
    'updated_by',
  ];

  public function branches()
  {
    return $this->hasMany(Branch::class, 'course_id', 'course_id');
  }
}
