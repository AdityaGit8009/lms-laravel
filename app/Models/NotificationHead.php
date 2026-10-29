<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationHead extends Model
{
  protected $table = 'tbl_notification_heads';
  protected $primaryKey = 'notification_head_id';

  protected $fillable = [
    'notification_head',
    'created_by',
  ];
}
