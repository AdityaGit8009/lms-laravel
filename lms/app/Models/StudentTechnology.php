<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTechnology extends Model
{
    protected $table = 'tbl_student_technology';

    protected $fillable = [
        'stu_id',
        'technology_id',
        'created_by',
        'updated_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'stu_id', 'stu_id');
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class, 'technology_id', 'technology_id');
    }
}