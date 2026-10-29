<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterStudent extends Model
{
  protected $table = 'tbl_register';
  protected $primaryKey = 'id';

  protected $fillable = [
    'name',
    'college',
    'regno',
    'course',
    'branch',
    'year',
    'mobile',
    'email',
    'password',
    'gender',
    'program',
    'location',
    'photo',
    'approval_status',
  ];

  protected $hidden = [
    'password',
  ];


  public function role()
  {
    return $this->belongsTo(Role::class, 'role_id', 'role_id');
  }

  public function getCourseNameAttribute(): string
  {
    $courses = [
      '1' => 'Diploma',
      '2' => 'B.Tech',
      '3' => 'BCA',
      '4' => 'PGDCA',
      '5' => 'MCA',
      '6' => 'M.Sc',
      '7' => 'B.A',
      '8' => 'B.Sc',
      '9' => 'M.Tech',
    ];

    return $courses[(string) $this->course] ?? ($this->course ?: '-');
  }

  public function getYearNameAttribute(): string
  {
    $years = [
      '1' => 'First Year',
      '2' => 'Second Year',
      '3' => 'Third Year',
      '4' => 'Fourth Year',
      '5' => 'Fifth Year',
    ];

    return $years[(string) $this->year] ?? ($this->year ?: '-');
  }

  public function getProgramNameAttribute(): string
  {
    $programs = [
      '12' => 'Summer Training 2025',
      '13' => 'In House Training',
      '15' => 'Certification Programme',
      '16' => 'Virtual Internship',
      '8'  => 'Apprenticeship-2025',
      '11' => 'Full Stack Development',
    ];

    return $programs[(string) $this->program] ?? ($this->program ?: '-');
  }

  public function getLocationNameAttribute(): string
  {
    $locations = [
      'House'   => 'Softpro House, 3/213 Sector-J, Jankipuram, Lucknow - 226021',
      'Academy' => 'Softpro India Full Stack Academy, 1/6 Vastu Khand, Gomti Nagar, Lucknow - 226010',
      'Noida'   => 'Softpro India Computer Technologies P Limited, Creatons Business Park, Ground Floor, H-35, Sector 63, Noida - 201301',
    ];

    return $locations[$this->location] ?? ($this->location ?: '-');
  }
}
