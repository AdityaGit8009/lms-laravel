<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
  use HasFactory;

  protected $table = 'tbl_subject';
  protected $primaryKey = 'subject_id';

  protected $fillable = [
    'subject_name',
    'syllabus',
    'subject_image',
    'created_by',
    'updated_by',
  ];

  public function topics()
  {
    return $this->hasMany(Topic::class, 'subject_id', 'subject_id');
  }

  public function uploadContents()
  {
    return $this->hasMany(UploadContent::class, 'subject_id', 'subject_id');
  }
}
