<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineTestQuestion extends Model
{
  protected $table = 'tbl_online_test_questions';
  protected $primaryKey = 'otq_id';

  protected $fillable = [
    'online_test_id',
    'question_no',
    'marks',
    'question',
    'option_a',
    'option_b',
    'option_c',
    'option_d',
    'correct_option',
  ];
}
