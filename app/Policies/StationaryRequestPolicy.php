<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StationaryRequest;
use Illuminate\Auth\Access\Response;

/**
 * StationaryRequest Policy
 * 
 * Determines authorization for stationary request actions
 */
class StationaryRequestPolicy
{
    /**
     * Determine if user can view all requests (depends on role)
     */
    public function viewAny(User $user): Response
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Others can view requests in their department or their own
        return Response::allow();
    }

    /**
     * Determine if user can view specific request
     */
    public function view(User $user, StationaryRequest $request): Response
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Requester can always view their own
        if ($request->requested_by === $user->id) {
            return Response::allow();
        }

        // HOD can view department requests
        if ($user->isHOD() && $request->department_id === $user->department_id) {
            return Response::allow();
        }

        // Principal can view all requests
        if ($user->isPrincipal()) {
            return Response::allow();
        }

        // Trust head can view all requests
        if ($user->isTrustHead()) {
            return Response::allow();
        }

        // Provider can view requests sent to them (through orders)
        if ($user->isProvider()) {
            return $request->orders()->exists() 
                ? Response::allow() 
                : Response::deny('Provider can only view assigned orders.');
        }

        return Response::deny('You are not authorized to view this request.');
    }

    /**
     * Determine if user can create request
     */
    public function create(User $user): Response
    {
        // Only teachers and HOD in their department can create
        if ($user->isTeacher() || ($user->isHOD() && $user->department_id)) {
            return Response::allow();
        }

        return Response::deny('Only teachers and HOD can create requests.');
    }

    /**
     * Determine if user can update request
     */
    public function update(User $user, StationaryRequest $request): Response
    {
        // Can only update pending requests
        if (!$request->isPending()) {
            return Response::deny('Cannot update requests that are not pending.');
        }

        // Only requester can update their own pending request
        if ($request->requested_by === $user->id) {
            return Response::allow();
        }

        // Admin can update any pending request
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('You can only update your own pending requests.');
    }

    /**
     * Determine if user can delete request
     */
    public function delete(User $user, StationaryRequest $request): Response
    {
        // Can only delete pending requests
        if (!$request->isPending()) {
            return Response::deny('Cannot delete requests that are not pending.');
        }

        // Only requester can delete their own request
        if ($request->requested_by === $user->id) {
            return Response::allow();
        }

        // Admin can delete any pending request
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('You can only delete your own pending requests.');
    }

    /**
     * Determine if user can approve request
     */
    public function approve(User $user, StationaryRequest $request): Response
    {
        // Cannot approve own request
        if ($request->requested_by === $user->id) {
            return Response::deny('You cannot approve your own request.');
        }

        // HOD approval (Level 1) - pending requests from their department
        if ($user->isHOD()) {
            if ($request->isPending() && $request->department_id === $user->department_id) {
                return Response::allow();
            }
            return Response::deny('HOD can only approve pending requests in their department.');
        }

        // Principal approval (Level 2) - hod_approved requests
        if ($user->isPrincipal()) {
            if ($request->isHodApproved()) {
                return Response::allow();
            }
            return Response::deny('Principal can approve HOD-approved requests.');
        }

        // Trust Head approval (Level 3) - principal_approved requests
        if ($user->isTrustHead()) {
            if ($request->isPrincipalApproved()) {
                return Response::allow();
            }
            return Response::deny('Trust Head can approve Principal-approved requests.');
        }

        // Admin can approve any request at any level
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to approve requests.');
    }

    /**
     * Determine if user can reject request
     */
    public function reject(User $user, StationaryRequest $request): Response
    {
        // Cannot reject own request
        if ($request->requested_by === $user->id) {
            return Response::deny('You cannot reject your own request.');
        }

        // Cannot reject completed or rejected requests
        if (!$request->canBeRejected()) {
            return Response::deny('Cannot reject requests that are completed or already rejected.');
        }

        // HOD can reject pending requests in their department
        if ($user->isHOD() && $request->department_id === $user->department_id) {
            return Response::allow();
        }

        // Principal can reject hod_approved requests
        if ($user->isPrincipal() && $request->isHodApproved()) {
            return Response::allow();
        }

        // Trust Head can reject principal_approved requests
        if ($user->isTrustHead() && $request->isPrincipalApproved()) {
            return Response::allow();
        }

        // Admin can reject any request
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Provider can reject sent_to_provider requests
        if ($user->isProvider() && $request->isSentToProvider()) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to reject this request.');
    }

    /**
     * Determine if user can send to provider (Admin only)
     */
    public function sendToProvider(User $user, StationaryRequest $request): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can send requests to provider.');
        }

        if (!$request->isTrustApproved()) {
            return Response::deny('Request must be trust-approved before sending to provider.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can mark as supplied (Provider only)
     */
    public function markSupplied(User $user, StationaryRequest $request): Response
    {
        if (!$user->isProvider()) {
            return Response::deny('Only providers can mark requests as supplied.');
        }

        if (!$request->isSentToProvider()) {
            return Response::deny('Request must be sent to provider before marking as supplied.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can view approval history
     */
    public function viewApprovals(User $user, StationaryRequest $request): Response
    {
        // Allow if user can view the request
        return $this->view($user, $request);
    }

    /**
     * Determine if user can add items to request
     */
    public function addItems(User $user, StationaryRequest $request): Response
    {
        // Can only add items to pending requests
        if (!$request->isPending()) {
            return Response::deny('Cannot add items to requests that are not pending.');
        }

        // Only requester can add items
        if ($request->requested_by === $user->id) {
            return Response::allow();
        }

        // Admin can add items
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('Only request creator can add items to pending requests.');
    }
}
