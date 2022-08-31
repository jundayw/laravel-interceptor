<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filter Driver
    |--------------------------------------------------------------------------
    |
    | Supported Drivers:
    | \Jundayw\LaravelInterceptor\Support\LocalFilter::class
    |
    | Default:
    | \Jundayw\LaravelInterceptor\Support\LocalFilter::class
    |
    */

    'driver' => \Jundayw\LaravelInterceptor\Support\LocalFilter::class,

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | This configuration value allows you to customize the table name.
    |
    */

    'table' => 'interceptor',

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "apc", "array", "database", "file",
    |         "memcached", "redis", "dynamodb", "octane", "null"
    |
    */

    'cache' => [
        'driver' => env('INTERCEPTOR_CACHE_DRIVER'),
        'ttl' => env('INTERCEPTOR_CACHE_TTL', 3600),
    ],

    'migration' => true,

];
