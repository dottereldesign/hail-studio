<?php

namespace App\Support;

use Illuminate\Http\Request;

class LogContext
{
    /**
     * @var array<int, string>
     */
    private const SENSITIVE_KEYS = [
        'password',
        'password_confirmation',
        'current_password',
        'token',
        'access_token',
        'refresh_token',
        'authorization',
        'cookie',
        'cookies',
        'session',
        'remember_token',
        'api_key',
        'secret',
        'x-csrf-token',
        '_token',
    ];

    public static function isDebugEnabled(): bool
    {
        return (bool) config('app.debug') && app()->environment('local');
    }

    public static function requestId(Request $request): ?string
    {
        $value = $request->attributes->get('request_id') ?? $request->header('X-Request-Id');

        return $value ? (string) $value : null;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function redact(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            $lowerKey = strtolower((string) $key);
            $isSensitive = in_array($lowerKey, self::SENSITIVE_KEYS, true);

            if ($isSensitive) {
                $sanitized[$key] = '[redacted]';
                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = self::redact($value);
                continue;
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    /**
     * @param array<string, mixed> $headers
     * @return array<string, mixed>
     */
    public static function redactHeaders(array $headers): array
    {
        return self::redact($headers);
    }
}
