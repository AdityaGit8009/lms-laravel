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
        'role',
        'password',
        'image',
        'is_block',
        'phone',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function isAdmin(): bool
    {
        return strtolower($this->role->role_name ?? '') === 'admin';
    }

    public function isEmployee(): bool
    {
        return strtolower($this->role->role_name ?? '') === 'employee';
    }

    public function isStudent(): bool
    {
        return strtolower($this->role->role_name ?? '') === 'student';
    }
}
