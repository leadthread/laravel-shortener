<?php

return [

    /**
     * The url shortening service to use. 
     * if set to NULL it will use all services in the accounts array
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

    'accounts' => [
        'google' => [
            [
                'token' => env('GOOGLE_SHORTENER_TOKEN_1'), 
            ],
            [
                'token' => env('GOOGLE_SHORTENER_TOKEN_2'), 
            ],
            [
                'token' => env('GOOGLE_SHORTENER_TOKEN_3'), 
            ],
        ],
        'bitly' => [
            [
                'token' => env('BITLY_SHORTENER_TOKEN_1'), 
            ],
            [
                'token' => env('BITLY_SHORTENER_TOKEN_2'), 
            ],
            [
                'token' => env('BITLY_SHORTENER_TOKEN_3'), 
            ],
        ], 
    ],
];
