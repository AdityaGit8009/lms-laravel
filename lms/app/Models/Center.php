<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
  protected $table = 'tbl_centers';
  protected $primaryKey = 'center_id';

  protected $fillable = [
    'center_name',
    'latitude',
    'longitude',
    'radius',
    'created_by',
    'updated_by',
  ];
}
