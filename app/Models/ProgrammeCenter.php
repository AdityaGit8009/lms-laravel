<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeCenter extends Model
{
  protected $table = 'tbl_programme_centers';
  protected $primaryKey = 'id';

  protected $fillable = [
    'session_id',
    'center_id',
    'programme_id',
    'created_by',
    'updated_by',
  ];

  public function session()
  {
    return $this->belongsTo(Session::class, 'session_id', 'session_id');
  }

  public function center()
  {
    return $this->belongsTo(Center::class, 'center_id', 'center_id');
  }

  public function programme()
  {
    return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
  }
}
