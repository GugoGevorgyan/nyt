<?php

declare(strict_types=1);

use Src\Core\Enums\ConstQueue;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */
    'default' => env('QUEUE_CONNECTION', 'sync'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */
    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => env('ON_QUEUE_DEFAULT', 'default'),
            'retry_after' => 864000,
            'block_for' => 0,
            'after_commit' => true,
        ],

        ConstQueue::TCP()->getValue() => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => ConstQueue::TCP()->getValue(),
            'retry_after' => 864000,
            'block_for' => 0,
        ],

        ConstQueue::OBSERVER()->getValue() => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => ConstQueue::OBSERVER()->getValue(),
            'retry_after' => 864000,
            'block_for' => 0,
        ],

        ConstQueue::LONG()->getValue() => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => ConstQueue::LONG()->getValue(),
            'retry_after' => 864000,
            'block_for' => 0,
            'timeout' => 0
        ],

        ConstQueue::BASE()->getValue() => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => ConstQueue::BASE()->getValue(),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
        ],

        ConstQueue::SCOUT()->getValue() => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => ConstQueue::SCOUT()->getValue(),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
        ],

        ///////////////////////////////////////////////////////////////////////////////////////

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('ON_QUEUE_DEFAULT', 'default'),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
            'after_commit' => true,
        ],

        ConstQueue::LONG()->getValue() => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => ConstQueue::LONG()->getValue(),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
            'timeout' => 0
        ],

        ConstQueue::TCP()->getValue() => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => ConstQueue::TCP()->getValue(),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
        ],

        ConstQueue::OBSERVER()->getValue() => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => ConstQueue::OBSERVER()->getValue(),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
        ],

        ConstQueue::BASE()->getValue() => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => ConstQueue::BASE()->getValue(),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
        ],

        ConstQueue::SCOUT()->getValue() => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => ConstQueue::SCOUT()->getValue(),
            'database' => env('REDIS_QUEUE_DB', 2),
            'retry_after' => 864000,
            'block_for' => 0,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */
    'failed' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
