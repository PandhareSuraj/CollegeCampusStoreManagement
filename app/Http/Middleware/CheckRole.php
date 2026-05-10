<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRole Middleware
 * 
 * Verifies that authenticated user has required role(s)
 * Usage: middleware('role:teacher,hod')
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // If no roles specified, allow all authenticated users
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user's role is in the allowed roles
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        return response()->json(
            ['message' => 'You do not have permission to access this resource.'],
            403
        );
    }
}
