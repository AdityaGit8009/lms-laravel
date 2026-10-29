<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockTest extends Model
{
  protected $table = 'tbl_mock_tests';
  protected $primaryKey = 'mock_id';

  protected $fillable = [
    'subject_id',
    'topic_id',
    'mock_test',
    'marks',
  ];
}
