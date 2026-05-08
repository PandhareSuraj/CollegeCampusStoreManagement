<?php

namespace App\Traits;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * HasWorkflowStatus Trait
 * 
 * Provides workflow status-related scopes for filtering requests
 * Centralizes status filtering logic across the application
 */
trait HasWorkflowStatus
{
    /**
     * Scope: Filter records by status
     */
    public function scopeByStatus(Builder $query, RequestStatus|string $status): Builder
    {
        $statusValue = $status instanceof RequestStatus ? $status->value : $status;
        return $query->where('status', $statusValue);
    }
    
    /**
     * Scope: Get pending requests
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->byStatus(RequestStatus::PENDING);
    }
    
    /**
     * Scope: Get approved requests (any approval level)
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereIn('status', [
            RequestStatus::HOD_APPROVED->value,
            RequestStatus::PRINCIPAL_APPROVED->value,
            RequestStatus::TRUST_APPROVED->value,
        ]);
    }
    
    /**
     * Scope: Get active requests (not completed or rejected)
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            RequestStatus::COMPLETED->value,
            RequestStatus::REJECTED->value,
        ]);
    }
    
    /**
     * Scope: Get completed requests
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->byStatus(RequestStatus::COMPLETED);
    }
    
    /**
     * Scope: Get rejected requests
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->byStatus(RequestStatus::REJECTED);
    }
    
    /**
     * Scope: Get requests sent to provider
     */
    public function scopeSentToProvider(Builder $query): Builder
    {
        return $query->byStatus(RequestStatus::SENT_TO_PROVIDER);
    }
    
    /**
     * Scope: Get requests in approval pipeline
     */
    public function scopeInApprovalPipeline(Builder $query): Builder
    {
        return $query->whereIn('status', [
            RequestStatus::PENDING->value,
            RequestStatus::HOD_APPROVED->value,
            RequestStatus::PRINCIPAL_APPROVED->value,
            RequestStatus::TRUST_APPROVED->value,
        ]);
    }
}
