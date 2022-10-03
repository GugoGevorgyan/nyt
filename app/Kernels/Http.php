<?php

declare(strict_types=1);

namespace App\Kernels;

use Fruitcake\Cors\HandleCors;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as BaseHttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;
use Src\Http\Middleware\App\ApiResponseInterceptor;
use Src\Http\Middleware\App\AppResponseInterceptor;
use Src\Http\Middleware\App\AppSystems;
use Src\Http\Middleware\App\DebugBarTest;
use Src\Http\Middleware\App\DetectFranchiseRegion;
use Src\Http\Middleware\App\Firewall;
use Src\Http\Middleware\App\HttpsProtocol;
use Src\Http\Middleware\App\Versioning;
use Src\Http\Middleware\AssignGuard;
use Src\Http\Middleware\Authenticate;
use Src\Http\Middleware\Client\CheckClientInOrder;
use Src\Http\Middleware\Client\CheckClientType;
use Src\Http\Middleware\Client\CheckLoginFormForUser;
use Src\Http\Middleware\Client\DetectClientInfoMiddleware;
use Src\Http\Middleware\Client\SetClientMiddleware;
use Src\Http\Middleware\CspSecure;
use Src\Http\Middleware\DetectAuthedUser;
use Src\Http\Middleware\Driver\CheckDriverData;
use Src\Http\Middleware\Driver\DriverCheckWaybill;
use Src\Http\Middleware\EncryptCookies;
use Src\Http\Middleware\Helpers\NumbersCleanSymbols;
use Src\Http\Middleware\Modules\CheckForMaintenanceMode;
use Src\Http\Middleware\Modules\CheckFranchiseHasUserModules;
use Src\Http\Middleware\Modules\CheckFranchiseModuleRoles;
use Src\Http\Middleware\Modules\CheckFranchiseModules;
use Src\Http\Middleware\Order\CheckCompanyClientOrder;
use Src\Http\Middleware\Order\CheckCompanyClientScheduledOrder;
use Src\Http\Middleware\RedirectAuthenticated;
use Src\Http\Middleware\Roles\PermissionMiddleware;
use Src\Http\Middleware\Roles\RoleMiddleware;
use Src\Http\Middleware\Roles\RoleOrPermissionMiddleware;
use Src\Http\Middleware\SystemWorker\InSession;
use Src\Http\Middleware\TrimStrings;
use Src\Http\Middleware\TrustProxies;
use Src\Http\Middleware\VerifyCsrfToken;

/**
 * Class Kernel
 * @package Src\Http
 */
class Http extends BaseHttpKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
//    protected $bootstrappers = [
//        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
//        \App\Configures\Config\LoadConfigurator::class,
//        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
//        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
//        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
//        \Illuminate\Foundation\Bootstrap\BootProviders::class,
//    ];

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
        HandleCors::class,
        HttpsProtocol::class,
        AppSystems::class,
        Firewall::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            'throttle:120,1',
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            //AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            CreateFreshApiToken::class,
            AppResponseInterceptor::class,
            CspSecure::class,
        ],

        'api' => [
            'throttle:180,1',
            'bindings',
//            ApiResponseInterceptor::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'guard_detect' => DetectAuthedUser::class,

        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'doNotCacheResponse' => DoNotCacheResponse::class,
        'can' => Authorize::class,
        'guest' => RedirectAuthenticated::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'clean.numbers' => NumbersCleanSymbols::class,
        'client' => CheckClientCredentials::class,

        'set_client_middleware' => SetClientMiddleware::class,
        'worker_in_session' => InSession::class,
        'assign.guard' => AssignGuard::class,
        'check_driver_data' => CheckDriverData::class,
        'check_franchise_modules' => CheckFranchiseModules::class,
        'check_franchise_module_roles' => CheckFranchiseModuleRoles::class,
        'check_franchise_has_user_modules' => CheckFranchiseHasUserModules::class,
        'check_client_in_order' => CheckClientInOrder::class,
        'detect_client_info' => DetectClientInfoMiddleware::class,
        'detect_franchise_region' => DetectFranchiseRegion::class,

        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,
        'company_have_user' => CheckCompanyClientOrder::class,
        'company_have_user_scheduled' => CheckCompanyClientScheduledOrder::class,
        'check.client.type' => CheckClientType::class,
        'check_login_form' => CheckLoginFormForUser::class,
        'check_waybill' => DriverCheckWaybill::class,

        'set_local' => AppSystems::class,
        'debug_test' => DebugBarTest::class,
        '_versioning' => Versioning::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        StartSession::class,
        ShareErrorsFromSession::class,
        Authenticate::class,
        AuthenticateSession::class,
        SubstituteBindings::class,
        Authorize::class,
    ];
}
