<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 * 
 * Base user model for all system users with role-based functionality
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'department_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the department this user belongs to
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all stationary requests created by this user
     */
    public function requests(): HasMany
    {
        return $this->hasMany(StationaryRequest::class, 'requested_by');
    }

    /**
     * Get all approvals made by this user
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'approved_by');
    }

    /**
     * Get all notifications for this user
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Role checking methods
     */
    public function isTeacher(): bool
    {
        return $this->role === UserRole::TEACHER->value;
    }

    public function isHOD(): bool
    {
        return $this->role === UserRole::HOD->value;
    }

    public function isPrincipal(): bool
    {
        return $this->role === UserRole::PRINCIPAL->value;
    }

    public function isTrustHead(): bool
    {
        return $this->role === UserRole::TRUST_HEAD->value;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN->value;
    }

    public function isProvider(): bool
    {
        return $this->role === UserRole::PROVIDER->value;
    }

    /**
     * Check if user can approve requests
     */
    public function canApprove(): bool
    {
        return in_array($this->role, [
            UserRole::HOD->value,
            UserRole::PRINCIPAL->value,
            UserRole::TRUST_HEAD->value,
            UserRole::ADMIN->value,
        ]);
    }

    /**
     * Scope: Get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Get only verified users
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
}
