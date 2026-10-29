<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentLoginLog extends Model
{
  protected $table = 'student_login_logs';
  protected $primaryKey = 'log_id';

  protected $fillable = [
    'user_id',
    'stu_id',
    'student_email',
    'student_mobile',
    'login_at',
    'logout_at',
    'last_seen_at',
    'status',
    'ip_address',
    'user_agent',
  ];

  protected $casts = [
    'login_at' => 'datetime',
    'logout_at' => 'datetime',
    'last_seen_at' => 'datetime',
  ];

  public function getSessionMinutesAttribute()
  {
    if (!$this->login_at) {
      return 0;
    }

    $end = $this->logout_at ?? $this->last_seen_at ?? now();

    return Carbon::parse($this->login_at)->diffInMinutes(Carbon::parse($end));
  }
}
