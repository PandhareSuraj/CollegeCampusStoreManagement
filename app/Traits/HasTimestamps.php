<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * HasTimestamps Trait
 * 
 * Provides common timestamp-related scopes and methods
 * Ensures consistent timestamp handling across models
 */
trait HasTimestamps
{
    /**
     * Initialize the trait
     */
    public function initializeHasTimestamps(): void
    {
        $this->dates = array_merge($this->dates ?? [], [
            'created_at',
            'updated_at',
        ]);
    }
    
    /**
     * Scope: Filter records created today
     */
    public function scopeCreatedToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }
    
    /**
     * Scope: Filter records created in the last N days
     */
    public function scopeCreatedInLastDays(Builder $query, int $days): Builder
    {
        return $query->whereDate('created_at', '>=', now()->subDays($days)->toDateString());
    }
    
    /**
     * Scope: Filter records created at start of today
     */
    public function scopeCreatedAfter(Builder $query, $date): Builder
    {
        return $query->whereDate('created_at', '>=', $date);
    }
    
    /**
     * Get human-readable time elapsed
     */
    public function timeAgo(): string
    {
        return $this->created_at?->diffForHumans() ?? 'Unknown';
    }
}
