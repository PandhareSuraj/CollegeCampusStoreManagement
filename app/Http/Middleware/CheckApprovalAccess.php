<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckApprovalAccess Middleware
 * 
 * Ensures user is authorized to perform approval actions
 * Validates that user is not approving their own request (conflict of interest)
 */
class CheckApprovalAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->route('request') ?? $request->input('request_id');
        
        if (!$requestId) {
            return response()->json(['message' => 'Request ID is required'], 400);
        }

        $stationaryRequest = \App\Models\StationaryRequest::find($requestId);

        if (!$stationaryRequest) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        // Check if user is trying to approve their own request
        if ($stationaryRequest->requested_by === $request->user()->id) {
            return response()->json(
                ['message' => 'You cannot approve your own request (conflict of interest)'],
                403
            );
        }

        // Store request in request for later use
        $request->merge(['stationaryRequest' => $stationaryRequest]);

        return $next($request);
    }
}
