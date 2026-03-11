<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile_phone',
        'mobile_verified_at',
        'country_code',
        'password',
        'security_pin',
        'kyc_status',
        'two_factor_enabled',
        'two_factor_type',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'failed_login_attempts',
        'account_locked_until',
        'last_login_at',
        'last_login_ip',
        'session_version',
        'is_flagged',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_pin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactions(): HasMany
    {
        // Transactions where user is sender or receiver
        // This is complex, usually we query Transaction model directly
        return $this->hasMany(Transaction::class, 'sender_wallet_id', 'id'); // Simplified
    }

    public function kyc_documents(): HasMany
    {
        return $this->hasMany(KycDocument::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function qr_codes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function hasVerifiedKyc(): bool
    {
        return $this->kyc_status === 'approved';
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
