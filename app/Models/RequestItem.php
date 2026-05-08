<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * RequestItem Model
 * 
 * Represents individual items within a stationary request
 */
class RequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Get the request this item belongs to
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(StationaryRequest::class);
    }

    /**
     * Get the product for this item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Boot method to handle events
     */
    protected static function boot()
    {
        parent::boot();

        // When item is created, update request total
        static::created(function ($item) {
            $item->request->total_amount = $item->request->calculateTotal();
            $item->request->save();
        });

        // When item is deleted, update request total
        static::deleted(function ($item) {
            $item->request->total_amount = $item->request->calculateTotal();
            $item->request->save();
        });
    }
}
