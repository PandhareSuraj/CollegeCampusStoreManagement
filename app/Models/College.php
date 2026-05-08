<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * College Model
 * 
 * Represents an educational college/institution in the system
 */
class College extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'city',
        'state',
        'postal_code',
        'phone',
        'email',
        'principal_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all departments in this college
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get count of departments
     */
    public function getDepartmentCount(): int
    {
        return $this->departments()->count();
    }

    /**
     * Scope: Get only active colleges
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
