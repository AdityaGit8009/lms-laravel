<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'tbl_attendance';
    protected $primaryKey = 'attend_id';
    public $timestamps = false;

    protected $fillable = [
        'attend_date',
        'attend_session',
        'attend_center',
        'attend_programme',
        'attend_technology',
        'attend_batch',
        'attend_student',
        'attendance',
        'attend_by',
        'added_date',
        'added_time',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'attend_student', 'stu_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'attend_session', 'session_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'attend_center', 'center_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'attend_programme', 'programme_id');
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class, 'attend_technology', 'technology_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'attend_batch', 'batch_id');
    }
}
