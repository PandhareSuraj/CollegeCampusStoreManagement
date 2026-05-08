<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Department Model
 * 
 * Represents a department within a college
 */
class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'college_id',
        'name',
        'code',
        'description',
        'head_name',
        'budget_code',
        'allocated_budget',
        'is_active',
    ];

    protected $casts = [
        'allocated_budget' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the college this department belongs to
     */
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    /**
     * Get all users in this department
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all requests from this department
     */
    public function requests(): HasMany
    {
        return $this->hasMany(StationaryRequest::class);
    }

    /**
     * Scope: Get only active departments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get count of users in department
     */
    public function getUserCount(): int
    {
        return $this->users()->count();
    }
}
