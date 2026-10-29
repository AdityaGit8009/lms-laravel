<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
  use HasFactory;

  protected $table = 'tbl_batch';
  protected $primaryKey = 'batch_id';

  protected $fillable = [
    'session_id',
    'programme_id',
    'technology_id',
    'batch_name',
    'created_by',
    'updated_by',
  ];

  public function session()
  {
    return $this->belongsTo(Session::class, 'session_id', 'session_id');
  }

  public function programme()
  {
    return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
  }

  public function technology()
  {
    return $this->belongsTo(Technology::class, 'technology_id', 'technology_id');
  }
}
