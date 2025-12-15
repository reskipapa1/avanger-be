<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://pinjaman-online-bay.vercel.app',
        // tambahkan origin lain kalau perlu, misal dev:
        'http://localhost:3000',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // false karena pakai personal access token (Bearer).
    'supports_credentials' => false,
];
