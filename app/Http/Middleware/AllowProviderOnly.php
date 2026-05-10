<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AllowProviderOnly Middleware
 * 
 * Restricts access to provider role users only
 */
class AllowProviderOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->isProvider()) {
            return response()->json(
                ['message' => 'Only providers can access this resource.'],
                403
            );
        }

        return $next($request);
    }
}
