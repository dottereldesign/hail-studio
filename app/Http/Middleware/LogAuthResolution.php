<?php

namespace App\Http\Middleware;

use App\Support\LogContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogAuthResolution
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (LogContext::isDebugEnabled()) {
            $user = $request->user();
            $context = [
                'request_id' => LogContext::requestId($request),
                'user_id' => $user?->id,
                'role' => $user?->role(),
                'organization_id' => $user?->currentOrganizationId(),
            ];

            Log::info($user ? 'auth.resolved' : 'auth.guest', $context);
        }

        return $next($request);
    }
}
