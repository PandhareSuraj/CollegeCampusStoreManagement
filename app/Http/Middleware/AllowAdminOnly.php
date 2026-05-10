<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AllowAdminOnly Middleware
 * 
 * Restricts access to admin role users only
 */
class AllowAdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->isAdmin()) {
            return response()->json(
                ['message' => 'Only administrators can access this resource.'],
                403
            );
        }

        return $next($request);
    }
}
