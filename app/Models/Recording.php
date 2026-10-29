<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
  protected $table = 'tbl_recordings';
  protected $primaryKey = 'rec_id';

  protected $fillable = [
    'subject_id',
    'topic_id',
    'video_Recording',
    'max_marks',
    'due_date',
  ];
}
