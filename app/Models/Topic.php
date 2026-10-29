<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
  protected $table = 'tbl_topic';
  protected $primaryKey = 'topic_id';

  protected $fillable = [
    'subject_id',
    'topic_name',
    'created_by',
    'updated_by',
  ];

  public function subject()
  {
    return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
  }

  public function uploadContents()
  {
    return $this->hasMany(UploadContent::class, 'topic_id', 'topic_id');
  }
}
