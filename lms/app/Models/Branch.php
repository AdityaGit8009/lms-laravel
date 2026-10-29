<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
  protected $table = 'tbl_branches';
  protected $primaryKey = 'branch_id';

  protected $fillable = [
    'course_id',
    'branch_name',
    'created_by',
    'updated_by',
  ];

  public function course()
  {
    return $this->belongsTo(Course::class, 'course_id', 'course_id');
  }
}
