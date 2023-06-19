<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\isAdmin::class,
        'project-manager' => \App\Http\Middleware\isProjectManager::class,
        'purchasing' => \App\Http\Middleware\isPurchasing::class,
        'logistic' => \App\Http\Middleware\isLogistic::class,
        'admin-gudang' => \App\Http\Middleware\isAdminGudang::class,
        'supervisor' => \App\Http\Middleware\isSupervisor::class,
        'admin-admingudang' => \App\Http\Middleware\isAdminOrAdminGudang::class,
        'admin-admingudang-logistic' => \App\Http\Middleware\isAdminOrAdminGudangOrLogistic::class,
        'admin-logistic' => \App\Http\Middleware\isAdminOrLogistic::class,
        'admin-projectmanager' => \App\Http\Middleware\isAdminOrProjectManager::class,
        'admin-projectmanager-supervisor' => \App\Http\Middleware\isAdminOrProjectManagerOrSupervisor::class,
        'admin-projectmanager-supervisor-admingudang' => \App\Http\Middleware\isAdminOrProjectManagerOrSupervisorOrAdminGudang::class,
        'admin-supervisor' => \App\Http\Middleware\isAdminOrSupervisor::class,
        'admin-purchasing' => \App\Http\Middleware\isAdminOrPurchasing::class,
        'admin-purchasing-admingudang-logistic' => \App\Http\Middleware\isAdminOrPurchasingOrAdminGudangOrLogistic::class,
        'admin-projectmanager-supervisor-admingudang-logistic' => \App\Http\Middleware\isAdminOrProjectManagerOrSupervisorOrAdminGudangOrLogistic::class,
    ];
}
