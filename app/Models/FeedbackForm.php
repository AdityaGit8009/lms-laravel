<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackForm extends Model
{
  protected $table = 'tbl_feedback_forms';
  protected $primaryKey = 'form_id';

  protected $fillable = [
    'public_key',
    'title',
    'description',
    'session_ids',
    'center_ids',
    'programme_ids',
    'technology_ids',
    'batch_ids',
    'form_status',
    'questions_json',
    'created_by',
  ];

  protected $casts = [
    'session_ids'    => 'array',
    'center_ids'     => 'array',
    'programme_ids'  => 'array',
    'technology_ids' => 'array',
    'batch_ids'      => 'array',
    'questions_json' => 'array',
  ];

  public function responses()
  {
    return $this->hasMany(FeedbackResponse::class, 'form_id', 'form_id');
  }
}
