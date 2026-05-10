<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * User Policy
 * 
 * Determines authorization for user management
 */
class UserPolicy
{
    /**
     * Determine if user can view all users
     */
    public function viewAny(User $user): Response
    {
        // Only admin can view all users
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('Only admin can view all users.');
    }

    /**
     * Determine if user can view specific user
     */
    public function view(User $user, User $model): Response
    {
        // User can always view themselves
        if ($user->id === $model->id) {
            return Response::allow();
        }

        // Admin can view all
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // HOD can view department users
        if ($user->isHOD() && $user->department_id === $model->department_id) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to view this user.');
    }

    /**
     * Determine if user can create user (Admin only)
     */
    public function create(User $user): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can create users.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can update user
     */
    public function update(User $user, User $model): Response
    {
        // User can update their own profile
        if ($user->id === $model->id) {
            return Response::allow();
        }

        // Only admin can update other users
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('You can only update your own profile.');
    }

    /**
     * Determine if user can delete user (Admin only)
     */
    public function delete(User $user, User $model): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can delete users.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can change user role
     */
    public function changeRole(User $user, User $model): Response
    {
        // Only admin can change roles
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can change user roles.');
        }

        return Response::allow();
    }

    /**
     * Determine if user can assign user to department
     */
    public function assignDepartment(User $user): Response
    {
        // Only admin can assign departments
        if (!$user->isAdmin()) {
            return Response::deny('Only admin can assign users to departments.');
        }

        return Response::allow();
    }
}
