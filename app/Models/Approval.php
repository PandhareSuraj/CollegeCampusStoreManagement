<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Approval Model
 * 
 * Tracks approval records and workflow history for requests
 */
class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'approved_by',
        'role',
        'status',
        'remarks',
        'approval_level',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Get the request this approval belongs to
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(StationaryRequest::class);
    }

    /**
     * Get the approver user
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Get approved records
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get rejected records
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get by approval level
     */
    public function scopeByLevel($query, int $level)
    {
        return $query->where('approval_level', $level);
    }
}
