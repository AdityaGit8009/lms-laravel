<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programme extends Model
{
  use SoftDeletes;

  protected $table = 'tbl_programme';
  protected $primaryKey = 'programme_id';

  protected $fillable = [
    'session_id',
    'programme_name',
    'duration',
    'created_by',
    'updated_by',
    'deleted_by',
    'restored_by',
    'restored_at',
  ];

  protected $dates = [
    'deleted_at',
    'restored_at',
  ];

  public function session()
  {
    return $this->belongsTo(Session::class, 'session_id', 'session_id');
  }

  public function programmeCenters()
  {
    return $this->hasMany(ProgrammeCenter::class, 'programme_id', 'programme_id');
  }

  public function technologies()
  {
    return $this->hasMany(Technology::class, 'programme_id', 'programme_id');
  }
}
