<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'two_factor_enabled',
        'two_factor_type',
        'two_factor_secret',
        'failed_login_attempts',
        'account_locked_until',
        'last_login_at',
        'last_login_ip',
        'ip_allowlist_enabled',
        'session_version',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'account_locked_until' => 'datetime',
        'last_login_at' => 'datetime',
        'ip_allowlist_enabled' => 'boolean',
    ];
}
