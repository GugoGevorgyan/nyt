<?php

declare(strict_types=1);

use Src\Core\Enums\ConstGuards;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'clients_web',
        'passwords' => 'clients'
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | clients are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    // Default Guard
    'guards' => [
        ConstGuards::CLIENTS_WEB()->getValue() => [
            'driver' => 'session',
            'provider' => 'clients',
        ],

        ConstGuards::CLIENTS_API()->getValue() => [
            'driver' => 'passport',
            'provider' => 'clients',
            'hash' => false,
        ],

        ConstGuards::BEFORE_CLIENTS_WEB()->getValue() => [
            'driver' => 'session',
            'provider' => 'before_clients',
        ],

        ConstGuards::DRIVERS_WEB()->getValue() => [
            'driver' => 'session',
            'provider' => 'drivers',
        ],

        ConstGuards::DRIVERS_API()->getValue() => [
            'driver' => 'passport',
            'provider' => 'drivers',
            'hash' => false,
        ],

        ConstGuards::SYSTEM_WORKERS_WEB()->getValue() => [
            'driver' => 'session',
            'provider' => 'system_workers',
        ],

        ConstGuards::SYSTEM_WORKERS_API()->getValue() => [
            'driver' => 'passport',
            'provider' => 'system_workers',
            'hash' => false,
        ],

        ConstGuards::ADMIN_CORPORATE_WEB()->getValue() => [
            'driver' => 'session',
            'provider' => 'admin_corporate',
        ],

        ConstGuards::ADMIN_CORPORATE_API()->getValue() => [
            'driver' => 'passport',
            'provider' => 'admin_corporate',
            'hash' => false,
        ],

        ConstGuards::ADMIN_SUPER_WEB()->getValue() => [
            'driver' => 'session',
            'provider' => 'admin_super',
        ],

        ConstGuards::ADMIN_SUPER_API()->getValue() => [
            'driver' => 'passport',
            'provider' => 'admin_super',
            'hash' => false,
        ],

        ConstGuards::API_TERMINALS()->getValue() => [
            'driver' => 'passport',
            'provider' => 'terminals',
            'hash' => false,
        ],
        // @TODO
        'api_clients' => [
            'driver' => 'passport',
            'provider' => 'api_client',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SuperFranchiser Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | clients are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'clients' => [
            'driver' => 'eloquent',
            'model' => Src\Models\Client\Client::class,
        ],

        'before_clients' => [
            'driver' => 'eloquent',
            'model' => Src\Models\Client\BeforeAuthClient::class,
        ],

        'admin_super' => [
            'driver' => 'eloquent',
            'model' => Src\Models\SystemUsers\SuperAdmin::class,
        ],

        'system_workers' => [
            'driver' => 'eloquent',
            'model' => Src\Models\SystemUsers\SystemWorker::class,
        ],

        'admin_corporate' => [
            'driver' => 'eloquent',
            'model' => Src\Models\Corporate\AdminCorporate::class,
        ],

        'drivers' => [
            'driver' => 'eloquent',
            'model' => Src\Models\Driver\Driver::class,
        ],

        'api_client' => [
            'driver' => 'eloquent',
            'model' => Src\Models\SystemUsers\ApiClient::class,
        ],

        'terminals' => [
            'driver' => 'eloquent',
            'model' => Src\Models\Terminal\Terminal::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'clients' => [
            'provider' => 'clients',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'before_auth_clients' => [
            'provider' => 'before_auth_clients',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'system_workers' => [
            'provider' => 'system_workers',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'drivers' => [
            'provider' => 'drivers',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'admin_super_web' => [
            'provider' => 'admin_super',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'admin_corporate_web' => [
            'provider' => 'admin_corporate',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'api_clients' => [
            'provider' => 'api_client',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
