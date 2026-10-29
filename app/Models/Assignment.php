<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
  protected $table = 'tbl_assignments';
  protected $primaryKey = 'assignment_id';

  protected $fillable = [
    'topic_id',
    'subject_id',
    'Assignment_Ques',
    'Assignment_file',
    'max_marks',
    'due_date',
  ];
}
