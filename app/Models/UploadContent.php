<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadContent extends Model
{
  protected $table = 'tbl_upload_content';
  protected $primaryKey = 'content_id';

  protected $fillable = [
    'subject_id',
    'topic_id',
    'title',
    'learning_objective',
    'content_body',
    'resource_file',
    'video_link',
    'trainer_task',
    'learning_outcomes',
    'created_by',
    'updated_by',
  ];

  public function subject()
  {
    return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
  }

  public function topic()
  {
    return $this->belongsTo(Topic::class, 'topic_id', 'topic_id');
  }
}
