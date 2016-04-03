<?php

return [

    /**
     * The url shortening service to use
     */
    'driver' => env('SHORTENER_DRIVER','bitly'),

    'cache' => [
        /**
         * Whether or not to use Laravels Cache driver
         */
        'enabled' => env('SHORTENER_CACHE_ENABLED',true),

        /**
         * The duration in minutes to remember the url in cache
         */
        'duration' => env('SHORTENER_CACHE_DURATION',1440),
    ];

    /**
     * Bitly settings
     */
    'bitly' => [
        'token' => env('BITLY_APP_TOKEN'),
    ], 
];
