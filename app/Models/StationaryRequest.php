<?php

namespace App\Models;

use App\Traits\HasWorkflowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StationaryRequest Model
 * 
 * Core model representing a stationary request in the approval workflow
 */
class StationaryRequest extends Model
{
    use HasFactory, SoftDeletes, HasWorkflowStatus;

    protected $fillable = [
        'department_id',
        'requested_by',
        'title',
        'description',
        'status',
        'total_amount',
        'remarks',
        'rejection_reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the department this request belongs to
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who requested
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get all items in this request
     */
    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    /**
     * Get all approvals for this request
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Get the order for this request
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Status check methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isHodApproved(): bool
    {
        return $this->status === 'hod_approved';
    }

    public function isPrincipalApproved(): bool
    {
        return $this->status === 'principal_approved';
    }

    public function isTrustApproved(): bool
    {
        return $this->status === 'trust_approved';
    }

    public function isSentToProvider(): bool
    {
        return $this->status === 'sent_to_provider';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get next approval role
     */
    public function getNextApprovalRole(): ?string
    {
        return match ($this->status) {
            'pending' => 'hod',
            'hod_approved' => 'principal',
            'principal_approved' => 'trust_head',
            'trust_approved' => 'admin',
            default => null,
        };
    }

    /**
     * Get current approval level
     */
    public function getCurrentApprovalLevel(): int
    {
        return match ($this->status) {
            'pending' => 0,
            'hod_approved' => 1,
            'principal_approved' => 2,
            'trust_approved' => 3,
            'sent_to_provider' => 4,
            'completed' => 5,
            default => -1,
        };
    }

    /**
     * Can request be rejected at current status
     */
    public function canBeRejected(): bool
    {
        return !in_array($this->status, ['completed', 'rejected']);
    }

    /**
     * Get approval history
     */
    public function getApprovalChain()
    {
        return $this->approvals()
            ->with('approver')
            ->orderBy('approval_level')
            ->get();
    }

    /**
     * Calculate total amount from items
     */
    public function calculateTotal(): float
    {
        return $this->items()->sum('subtotal');
    }
}
