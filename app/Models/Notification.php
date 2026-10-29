<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  protected $table = 'tbl_notification';
  protected $primaryKey = 'notification_id';
  public $timestamps = true;
  protected $fillable = [
    'session_id',
    'center_id',
    'programme_id',
    'technology_id',
    'batch_id',
    'notification_head_id',
    'title',
    'notification_file',
    'description',
    'created_by',
    'updated_by',
    'created_at',
    'updated_at',
  ];
}
