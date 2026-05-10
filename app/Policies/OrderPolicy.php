<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\Response;

/**
 * Order Policy
 * 
 * Determines authorization for order actions
 */
class OrderPolicy
{
    /**
     * Determine if user can view all orders
     */
    public function viewAny(User $user): Response
    {
        // Admin and provider can view all orders
        if ($user->isAdmin() || $user->isProvider()) {
            return Response::allow();
        }

        // HOD, Principal, TrustHead can view
        if ($user->isHOD() || $user->isPrincipal() || $user->isTrustHead()) {
            return Response::allow();
        }

        // Teachers can only view their own request's orders
        return Response::allow();
    }

    /**
     * Determine if user can view specific order
     */
    public function view(User $user, Order $order): Response
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Provider can view all
        if ($user->isProvider()) {
            return Response::allow();
        }

        // Requester can view their request's order
        if ($order->request->requested_by === $user->id) {
            return Response::allow();
        }

        // HOD can view department request orders
        if ($user->isHOD() && $order->request->department_id === $user->department_id) {
            return Response::allow();
        }

        // Principal and TrustHead can view all
        if ($user->isPrincipal() || $user->isTrustHead()) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to view this order.');
    }

    /**
     * Determine if user can create order (Admin only)
     */
    public function create(User $user): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can create orders.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can update order
     */
    public function update(User $user, Order $order): Response
    {
        // Only admin can update orders
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can update orders.');
        }

        // Can only update pending orders
        if (!$order->isPending() && !$order->isConfirmed()) {
            return Response::deny('Cannot update orders that are shipped or delivered.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can update delivery status
     */
    public function updateDeliveryStatus(User $user, Order $order): Response
    {
        // Only provider can update delivery status
        if (!$user->isProvider()) {
            return Response::deny('Only provider can update delivery status.');
        }

        if ($order->isDelivered()) {
            return Response::deny('This order is already delivered.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can delete order
     */
    public function delete(User $user, Order $order): Response
    {
        // Only admin can delete pending orders
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can delete orders.');
        }

        if ($order->isPending()) {
            return Response::allow();
        }

        return Response::deny('Can only delete pending orders.');
    }

    /**
     * Determine if user can confirm order
     */
    public function confirm(User $user, Order $order): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can confirm orders.');
        }

        if (!$order->isPending()) {
            return Response::deny('Only pending orders can be confirmed.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can receive items
     */
    public function receiveItems(User $user, Order $order): Response
    {
        if (!$user->isProvider()) {
            return Response::deny('Only provider can receive items.');
        }

        if (!$order->isShipped() && !$order->isConfirmed()) {
            return Response::deny('Order must be shipped or confirmed.');
        }

        return Response::allow();
    }
}
