<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
  protected $table = 'tbl_session';
  protected $primaryKey = 'session_id';

  public $timestamps = false;

  protected $fillable = [
    'session_name',
    'status',
    'created_by',
    'updated_by',
  ];
  public function programmes()
  {
    return $this->hasMany(Programme::class, 'session_id', 'session_id');
  }
  public function batches()
  {
    return $this->hasMany(Batch::class, 'session_id', 'session_id');
  }
}
