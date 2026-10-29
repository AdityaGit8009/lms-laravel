<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
  use HasFactory;

  protected $table = 'tbl_programme';
  protected $primaryKey = 'programme_id';

  protected $fillable = [
    'session_id',
    'programme_name',
    'duration',
    'created_by',
    'updated_by',
  ];

  public function session()
  {
    return $this->belongsTo(Session::class, 'session_id', 'session_id');
  }
  public function technologies()
  {
    return $this->hasMany(Technology::class, 'programme_id', 'programme_id');
  }
  public function batches()
  {
    return $this->hasMany(Batch::class, 'programme_id', 'programme_id');
  }
}
