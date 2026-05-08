<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Order Model
 * 
 * Represents purchase orders created from approved requests
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_id',
        'vendor_id',
        'order_number',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'total_amount',
        'status',
        'quantity_expected',
        'quantity_received',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the request this order belongs to
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(StationaryRequest::class);
    }

    /**
     * Get the vendor for this order
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get all items in this order
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Status check methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Scope: Get pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get confirmed orders
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: Get delivered orders
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage(): float
    {
        if ($this->quantity_expected == 0) {
            return 0;
        }

        return ($this->quantity_received / $this->quantity_expected) * 100;
    }
}
