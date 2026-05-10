<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureDepartmentAssigned Middleware
 * 
 * Ensures that user is assigned to a department
 * Required for HOD and Teacher roles
 */
class EnsureDepartmentAssigned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // HOD and Teacher roles must have department assigned
        if (($user->isTeacher() || $user->isHOD()) && !$user->department_id) {
            return response()->json(
                ['message' => 'Your user account is not assigned to a department.'],
                403
            );
        }

        return $next($request);
    }
}
