<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
        'image',
        'is_block',
        'email_verified_at',
        'approval_status',
        'college',
        'regno',
        'course',
        'branch',
        'year',
        'gender',
        'program',
        'location',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'mobile',
        'course_name',
        'year_name',
        'program_name',
        'location_name',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getMobileAttribute(): ?string
    {
        return $this->phone;
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

        $course = (string) ($this->course ?? '');
        return $courses[$course] ?? ($this->course ?: '-');
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

        $year = (string) ($this->year ?? '');
        return $years[$year] ?? ($this->year ?: '-');
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

        $program = (string) ($this->program ?? '');
        return $programs[$program] ?? ($this->program ?: '-');
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

    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function isAdmin(): bool
    {
        return strtolower((string) $this->role) === 'admin';
    }

    public function isEmployee(): bool
    {
        return strtolower((string) $this->role) === 'employee';
    }

    public function isStudent(): bool
    {
        return strtolower((string) $this->role) === 'student';
    }
}
