<?php

namespace App\Http\Middleware;

use App\Support\LogContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RequestCorrelationId
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->header('X-Request-Id') ?? (string) Str::uuid();
        $request->attributes->set('request_id', $requestId);
        $start = microtime(true);

        if (LogContext::isDebugEnabled()) {
            Log::info('request.start', [
                'request_id' => $requestId,
                'method' => $request->method(),
                'path' => $request->getPathInfo(),
                'ip' => $request->ip(),
            ]);
        }

        $response = $next($request);
        $response->headers->set('X-Request-Id', $requestId);

        if (LogContext::isDebugEnabled()) {
            $durationMs = (int) round((microtime(true) - $start) * 1000);
            Log::info('request.end', [
                'request_id' => $requestId,
                'method' => $request->method(),
                'path' => $request->getPathInfo(),
                'status' => $response->getStatusCode(),
                'duration_ms' => $durationMs,
            ]);
        }

        return $response;
    }
}
