<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\RequestCorrelationId::class,
            \App\Http\Middleware\LogAuthResolution::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (\Throwable $exception): void {
            if (! \App\Support\LogContext::isDebugEnabled()) {
                return;
            }

            $request = request();
            if (! $request instanceof \Illuminate\Http\Request) {
                return;
            }

            $user = $request->user();

            \Illuminate\Support\Facades\Log::error('exception.reported', [
                'request_id' => \App\Support\LogContext::requestId($request),
                'message' => $exception->getMessage(),
                'exception' => $exception::class,
                'path' => $request->path(),
                'method' => $request->method(),
                'status' => method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : null,
                'user_id' => $user?->id,
                'role' => $user?->role(),
                'organization_id' => $user?->currentOrganizationId(),
                'input' => \App\Support\LogContext::redact($request->all()),
                'query' => \App\Support\LogContext::redact($request->query()),
                'headers' => \App\Support\LogContext::redactHeaders($request->headers->all()),
            ]);
        });
    })->create();
