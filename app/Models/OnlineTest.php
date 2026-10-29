<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineTest extends Model
{
  protected $table = 'tbl_online_tests';
  protected $primaryKey = 'online_test_id';

  protected $fillable = [
    'subject_id',
    'topic_id',
    'total_questions',
  ];

  public function questions()
  {
    return $this->hasMany(OnlineTestQuestion::class, 'online_test_id', 'online_test_id');
  }
}
