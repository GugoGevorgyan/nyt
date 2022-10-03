<?php

declare(strict_types=1);

use ServiceEntity\GeoIP\Services\MaxMindDatabase;
use ServiceEntity\GeoIP\Services\MaxMindWebService;

return [

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for when a location is not found
    | for the IP provided.
    |
    */
    'log_failures' => true,

    /*
    |--------------------------------------------------------------------------
    | Include ImplementCurrency in Results
    |--------------------------------------------------------------------------
    |
    | When enabled the system will do it's best in deciding the user's currency
    | by matching their ISO code to a preset list of currencies.
    |
    */
    'include_currency' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Service
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default storage driver that should be used
    | by the framework.
    |
    | Supported: "maxmind_database", "maxmind_api", "ipapi"
    |
    */
    'service' => 'maxmind_database',

    /*
    |--------------------------------------------------------------------------
    | Storage Specific Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many storage drivers as you wish.
    |
    */
    'services' => [

        'maxmind_database' => [
            'class' => MaxMindDatabase::class,
            'database_path' => storage_path('geo/geoip.mmdb'),
            'update_url' => sprintf(
                'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&license_key=%s&suffix=tar.gz',
                env('MAXMIND_LICENSE_KEY')
            ),
            'locales' => ['en'],
        ],

        'maxmind_api' => [
            'class' => MaxMindWebService::class,
            'user_id' => env('MAXMIND_USER_ID'),
            'license_key' => env('MAXMIND_LICENSE_KEY'),
            'locales' => ['en'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Cache Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the type of caching that should be used
    | by the package.
    |
    | Options:
    |
    |  all  - All location are cached
    |  some - Cache only the requesting user
    |  none - Disable cached
    |
    */
    'cache' => 'all',

    /*
    |--------------------------------------------------------------------------
    | Cache Tags
    |--------------------------------------------------------------------------
    |
    | Cache tags are not supported when using the file or database cache
    | drivers in Laravel. This is done so that only locations can be cleared.
    |
    */
    'cache_tags' => ['geoip-location'],

    /*
    |--------------------------------------------------------------------------
    | Cache Expiration
    |--------------------------------------------------------------------------
    |
    | Define how long cached location are valid.
    |
    */
    'cache_expires' => 30,

    /*
    |--------------------------------------------------------------------------
    | Default Location
    |--------------------------------------------------------------------------
    |
    | Return when a location is not found.
    |
    */
    'default_location' => [
        'ip' => '127.0.0.0',
        'iso_code' => 'AM',
        'country' => 'Armenia',
        'city' => 'Yerevan',
        'state' => 'ER',
        'state_name' => 'Yerevan',
        'postal_code' => '06510',
        'lat' => 40.1817,
        'lon' => 44.5099,
        'timezone' => 'Asia/Yerevan',
        'continent' => 'AS',
        'default' => true,
        'currency' => 'AMD',
    ],
];
