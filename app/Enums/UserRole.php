<?php

namespace App\Enums;

/**
 * User Role Enumeration
 * 
 * Defines all user roles in the Campus Store Management System
 * Each role has specific permissions and workflow responsibilities
 */
enum UserRole: string
{
    // Educational Institution Roles
    case TEACHER = 'teacher';           // Creates stationary requests
    case HOD = 'hod';                   // First level approval (Head of Department)
    case PRINCIPAL = 'principal';       // Second level approval
    case TRUST_HEAD = 'trust_head';     // Third level approval
    
    // Administrative Roles
    case ADMIN = 'admin';               // System administrator, processes orders
    case PROVIDER = 'provider';         // Vendor/Supplier role
    
    /**
     * Get the display name of the role
     */
    public function label(): string
    {
        return match($this) {
            self::TEACHER => 'Teacher',
            self::HOD => 'Head of Department',
            self::PRINCIPAL => 'Principal',
            self::TRUST_HEAD => 'Trust Head',
            self::ADMIN => 'Administrator',
            self::PROVIDER => 'Provider',
        };
    }
    
    /**
     * Get the description of the role
     */
    public function description(): string
    {
        return match($this) {
            self::TEACHER => 'Creates and submits stationary requests',
            self::HOD => 'Reviews and approves/rejects requests from department',
            self::PRINCIPAL => 'Reviews HOD approved requests',
            self::TRUST_HEAD => 'Final approval authority before sending to provider',
            self::ADMIN => 'Manages orders and system administration',
            self::PROVIDER => 'Fulfills approved orders',
        };
    }
    
    /**
     * Get all approval roles in order of workflow
     */
    public static function approvalHierarchy(): array
    {
        return [
            self::HOD,
            self::PRINCIPAL,
            self::TRUST_HEAD,
        ];
    }
    
    /**
     * Check if role can approve requests
     */
    public function canApprove(): bool
    {
        return in_array($this, self::approvalHierarchy());
    }
    
    /**
     * Check if role is administrative
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
    
    /**
     * Get all roles as array [value => label]
     */
    public static function toArray(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
