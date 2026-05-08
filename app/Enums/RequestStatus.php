<?php

namespace App\Enums;

/**
 * Stationary Request Status Enumeration
 * 
 * Tracks the workflow status of stationary requests through the approval pipeline
 * and fulfillment process
 */
enum RequestStatus: string
{
    // Pending states
    case PENDING = 'pending';                           // Awaiting HOD approval
    
    // Approval pipeline states
    case HOD_APPROVED = 'hod_approved';                // Approved by Head of Department
    case PRINCIPAL_APPROVED = 'principal_approved';    // Approved by Principal
    case TRUST_APPROVED = 'trust_approved';            // Approved by Trust Head
    
    // Fulfillment states
    case SENT_TO_PROVIDER = 'sent_to_provider';        // Order sent to vendor
    case COMPLETED = 'completed';                       // Request fulfilled and completed
    
    // Rejection states
    case REJECTED = 'rejected';                         // Request rejected at any stage
    
    /**
     * Get the display name of the status
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::HOD_APPROVED => 'HOD Approved',
            self::PRINCIPAL_APPROVED => 'Principal Approved',
            self::TRUST_APPROVED => 'Trust Approved',
            self::SENT_TO_PROVIDER => 'Sent to Provider',
            self::COMPLETED => 'Completed',
            self::REJECTED => 'Rejected',
        };
    }
    
    /**
     * Get the badge color for UI display
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::HOD_APPROVED => 'info',
            self::PRINCIPAL_APPROVED => 'info',
            self::TRUST_APPROVED => 'info',
            self::SENT_TO_PROVIDER => 'primary',
            self::COMPLETED => 'success',
            self::REJECTED => 'danger',
        };
    }
    
    /**
     * Get the Tailwind CSS class for badge styling
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'badge-yellow',
            self::HOD_APPROVED => 'badge-blue',
            self::PRINCIPAL_APPROVED => 'badge-blue',
            self::TRUST_APPROVED => 'badge-blue',
            self::SENT_TO_PROVIDER => 'badge-indigo',
            self::COMPLETED => 'badge-green',
            self::REJECTED => 'badge-red',
        };
    }
    
    /**
     * Get the next status in the approval workflow
     */
    public function nextInWorkflow(): ?self
    {
        return match($this) {
            self::PENDING => self::HOD_APPROVED,
            self::HOD_APPROVED => self::PRINCIPAL_APPROVED,
            self::PRINCIPAL_APPROVED => self::TRUST_APPROVED,
            self::TRUST_APPROVED => self::SENT_TO_PROVIDER,
            self::SENT_TO_PROVIDER => self::COMPLETED,
            default => null,
        };
    }
    
    /**
     * Check if status is in approval workflow
     */
    public function isInApprovalPipeline(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::HOD_APPROVED,
            self::PRINCIPAL_APPROVED,
            self::TRUST_APPROVED,
        ]);
    }
    
    /**
     * Check if request is still active/processing
     */
    public function isActive(): bool
    {
        return $this !== self::COMPLETED && $this !== self::REJECTED;
    }
    
    /**
     * Check if request can be rejected at current status
     */
    public function canBeRejected(): bool
    {
        return $this->isActive();
    }
    
    /**
     * Get current approval level (0-based index)
     */
    public function approvalLevel(): int
    {
        return match($this) {
            self::PENDING => 0,
            self::HOD_APPROVED => 1,
            self::PRINCIPAL_APPROVED => 2,
            self::TRUST_APPROVED => 3,
            self::SENT_TO_PROVIDER => 4,
            self::COMPLETED => 5,
            self::REJECTED => -1,
        };
    }
    
    /**
     * Get all approval pipeline statuses
     */
    public static function approvalPipeline(): array
    {
        return [
            self::PENDING,
            self::HOD_APPROVED,
            self::PRINCIPAL_APPROVED,
            self::TRUST_APPROVED,
        ];
    }
    
    /**
     * Get all statuses as array [value => label]
     */
    public static function toArray(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
