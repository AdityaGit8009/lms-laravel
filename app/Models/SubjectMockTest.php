<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectMockTest extends Model
{
  protected $table = 'tbl_subject_mock_tests';
  protected $primaryKey = 'subject_mock_test_id';

  protected $fillable = [
    'subject_id',
    'title',
    'upload_file',
    'max_marks',
    'mock_test',
    'status',
    'created_by',
    'updated_by',
  ];

  public function subject()
  {
    return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
  }
}
