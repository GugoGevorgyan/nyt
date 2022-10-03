<?php

declare(strict_types=1);

return [
    'app_url' => env('APP_URL'),
    'language' => env('DEFAULT_LOCALE'),
    'oauth_prefix' => 'nyt-api',
    'server_ip' => env('SERVER_IP'),

    'app_franchise_admin_url' => env('APP_WORKER_URL'),
    'app_super_admin_url' => env('APP_SUPER_ADMIN_URL'),
    'app_corporate_admin_url' => env('APP_CORPORATE_ADMIN_URL'),

    'fire_server_key' => env('FIRE_SERVER_KEY'),
    'fictive_mail' => env('FICTIVE_MAIL'),

    'y_geocode' => env('Y_GEO_LOCATION'),
    'y_matrix' => env('Y_GEO_MATRIX'),
    'y_route' => env('Y_GEO_ROUTE'),

    'driver_view_radius' => env('DRIVER_VIEW_RADIUS'),
    'worker_driver_view_radius' => env('WORKER_DRIVER_VIEW_RADIUS'),
    'driver_search_first_radius' => env('DRIVER_SEARCH_FIRST_RADIUS'),
    'driver_search_second_radius' => env('DRIVER_SEARCH_SECOND_RADIUS'),
    'driver_search_thread_radius' => env('DRIVER_SEARCH_THREAD_RADIUS'),
    'favorite_driver_search_distance' => env('FAVORITE_DRIVER_SEARCH_RADIUS'),
    'driver_response_time' => env('DRIVER_RESPONSE_TIME'),
    'allowed_distance_driver' => env('DRIVER_ALLOWED_DISTANCE'),
    'allowed_duration_driver' => env('DRIVER_ALLOWED_DURATION'),
    'taxi_search_time' => env('TAXI_SEARCH_TIME'),
    'taxi_waiting_time' => env('TAXI_WAITING_TIME'),
    'costing_interval' => env('COSTING_INTERVAL'),
    'distance_calc_value' => env('CALCULATED_DISTANCE_POINTS'),
    'price_send_interval' => env('SEND_CALCULATED_PRICE_INTERVAL'),
    'accept_code_expired' => env('ACCEPT_CODE_EXPIRE_TIME'),
    'speed_limit' => env('DRIVER_SPEED_LIMIT'),
    'large_order' => env('LARGE_ORDER'),
    'order_end_dirty' => env('ORDER_END_DIRTY_DISTANCE'),
    'common_order_timeout' => env('COMMON_ORDER_TIMEOUT'),
    'end_distance_calculate' => env('ORDER_END_CALCULATE_DISTANCE'),
    'pr_timeout' => env('PREORDER_STARTED_TIMEOUT', 30),

    'sms_url' => env('SMS_URI'),
    'sms_user' => env('SMS_USER'),
    'sms_password' => env('SMS_PASSWORD'),
    'sms_sender' => env('SMS_SENDER'),
    'sms_time' => env('SMS_ACCEPT_SHELF_TIME'),
    'sms_hostname' => env('APP_NAME'),

    'captcha_enabled' => env('CAPTCHA_ENABLED', true),
    'captcha_key' => env('CAPTCHA_KEY'),
    'captcha_secret' => env('CAPTCHA_SECRET'),
    'captcha_check_url' => 'https://www.google.com/recaptcha/api/siteverify',

    'logo_path' => storage_path('public'.DIRECTORY_SEPARATOR.'franchisee_log'),

    'push' => [
        'size_limit' => '6000', // in bytes
        'base_path' => '/',
        'exclude_keywords' => []
    ]
];
