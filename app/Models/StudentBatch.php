<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBatch extends Model
{
  protected $table = 'tbl_student_batch';
  protected $primaryKey = 'id';

  protected $fillable = [
    'stu_id',
    'batch_id',
    'is_active',
    'assigned_at',
    'removed_at',
    'shift_reason',
    'created_by',
    'updated_by',
  ];
  public $timestamps = true;

  public function student()
  {
    return $this->belongsTo(Student::class, 'stu_id', 'stu_id');
  }

  public function batch()
  {
    return $this->belongsTo(Batch::class, 'batch_id', 'batch_id');
  }
}
