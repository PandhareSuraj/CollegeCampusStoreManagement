<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Vendor Model
 * 
 * Represents suppliers/vendors who fulfill orders
 */
class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'gst_number',
        'bank_details',
        'is_active',
        'total_supplies',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_supplies' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all orders for this vendor
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope: Get only active vendors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get count of orders
     */
    public function getOrderCount(): int
    {
        return $this->orders()->count();
    }
}
