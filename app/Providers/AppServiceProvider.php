<?php

namespace App\Providers;

use App\Models\Membership;
use App\Models\User;
use App\Support\LogContext;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('components.manage', function (User $user): bool {
            return $user->hasAnyRole([
                Membership::ROLE_OWNER,
                Membership::ROLE_ADMIN,
                Membership::ROLE_EDITOR,
            ]);
        });

        Gate::after(function (?User $user, string $ability, mixed $result, array $arguments): void {
            if ($result !== false || ! LogContext::isDebugEnabled()) {
                return;
            }

            Log::warning('auth.denied', [
                'ability' => $ability,
                'user_id' => $user?->id,
                'role' => $user?->role(),
                'organization_id' => $user?->currentOrganizationId(),
            ]);
        });
    }
}
