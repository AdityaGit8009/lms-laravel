<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
  use HasFactory;

  protected $table = 'tbl_technology';
  protected $primaryKey = 'technology_id';

  protected $fillable = [
    'session_id',
    'programme_id',
    'technology_name',
    'technology_image',
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

  public function batches()
  {
    return $this->hasMany(Batch::class, 'technology_id', 'technology_id');
  }
}
