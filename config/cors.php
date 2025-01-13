<?php

//return [
//
//    /*
//    |--------------------------------------------------------------------------
//    | Cross-Origin Resource Sharing (CORS) Configuration
//    |--------------------------------------------------------------------------
//    |
//    | Here you may configure your settings for cross-origin resource sharing
//    | or "CORS". This determines what cross-origin operations may execute
//    | in web browsers. You are free to adjust these settings as needed.
//    |
//    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
//    |
//    */
//
//    'paths' => ['api/*', 'sanctum/csrf-cookie'],
//
//    'allowed_methods' => ['*'],
//
//    'allowed_origins' => ['*'],
//
//    'allowed_origins_patterns' => [],
//
//    'allowed_headers' => ['*'],
//
//    'exposed_headers' => [],
//
//    'max_age' => 0,
//
//    'supports_credentials' => false,
//
//];


return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Include the sanctum/csrf-cookie route
    'allowed_methods' => ['*'], // Allow all HTTP methods
    'allowed_origins' => [
        '*',
        'https://api.nextlevelitsolution.com/',
        'http://localhost:3000', // React or Vue app running locally
        'http://192.168.0.108:3000', // Another device on the local network
    ],
    'allowed_origins_patterns' => [], // Use if you need dynamic patterns
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => ['*'],
    'max_age' => 0,
    'supports_credentials' => true, // Important for Sanctum
];

