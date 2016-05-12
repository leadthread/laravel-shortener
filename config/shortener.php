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
    ],

    /**
     * Bitly settings
     */
    'bitly' => [
        [
            'username' => env('BITLY_APP_USERNAME_1'), 
            'password' => env('BITLY_APP_PASSWORD_1')
        ],
        [
            'username' => env('BITLY_APP_USERNAME_2'), 
            'password' => env('BITLY_APP_PASSWORD_2')
        ],
        [
            'username' => env('BITLY_APP_USERNAME_3'), 
            'password' => env('BITLY_APP_PASSWORD_3')
        ],
    ], 
];
