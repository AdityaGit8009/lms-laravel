<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupNotification extends Model
{
  protected $table = 'tbl_popup_notification';
  protected $primaryKey = 'popup_notification_id';
  public $timestamps = true;

  protected $fillable = [
    'session_ids',
    'center_ids',
    'programme_ids',
    'technology_ids',
    'batch_ids',
    'title',
    'popup_from',
    'popup_to',
    'image',
    'description',
    'created_by',
    'updated_by',
    'created_at',
    'updated_at',
  ];

  protected $casts = [
    'session_ids'    => 'array',
    'center_ids'     => 'array',
    'programme_ids'  => 'array',
    'technology_ids' => 'array',
    'batch_ids'      => 'array',
  ];
}
