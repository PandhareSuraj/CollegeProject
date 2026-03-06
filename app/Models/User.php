<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * User Role Constants - Campus Store Management System
     * 
     * ADMIN: System Administrator with full access
     * TEACHER: Faculty member who submits requests
     * HOD: Head of Department who approves departmental requests
     * PRINCIPAL: College Principal who approves principal-level requests
     * TRUST_HEAD: Trust/Organization Head overseeing multiple institutions
     * PROVIDER: Vendor/Supplier who fulfills orders
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_TEACHER = 'teacher';
    public const ROLE_HOD = 'hod';
    public const ROLE_PRINCIPAL = 'principal';
    public const ROLE_TRUST_HEAD = 'trust_head';
    public const ROLE_PROVIDER = 'provider';

    /**
     * All available roles in the system
     */
    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_TEACHER,
        self::ROLE_HOD,
        self::ROLE_PRINCIPAL,
        self::ROLE_TRUST_HEAD,
        self::ROLE_PROVIDER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the department this user belongs to
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get requests created by this user
     */
    public function stationaryRequests(): HasMany
    {
        return $this->hasMany(StationaryRequest::class, 'requested_by');
    }

    /**
     * Get approvals by this user
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'approved_by');
    }

    /**
     * Purchase committees this user belongs to
     */
    public function purchaseCommittees()
    {
        return $this->belongsToMany(PurchaseCommittee::class, 'committee_user', 'user_id', 'committee_id');
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is HOD
     */
    public function isHOD(): bool
    {
        return $this->role === 'hod';
    }

    /**
     * Check if user is Principal
     */
    public function isPrincipal(): bool
    {
        return $this->role === 'principal';
    }

    /**
     * Check if user is Trust Head
     */
    public function isTrustHead(): bool
    {
        return $this->role === 'trust_head';
    }

    /**
     * Check if user is Provider
     */
    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }
    }

