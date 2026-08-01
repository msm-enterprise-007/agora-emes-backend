<?php

return [

    'olax' => [
        'host' => env('OLAX_HOST', '192.168.150.1'),
        'username' => env('OLAX_USERNAME'),
        'password' => env('OLAX_PASSWORD'),
        'timeout' => env('OLAX_TIMEOUT', 5),
    ],

    'mikrotik' => [
        'host' => env('MIKROTIK_HOST', '192.168.88.1'),
        'username' => env('MIKROTIK_USERNAME'),
        'password' => env('MIKROTIK_PASSWORD'),
        'port' => env('MIKROTIK_PORT', 8728),
        'ssl_port' => env('MIKROTIK_SSL_PORT', 8729),
        'timeout' => env('MIKROTIK_TIMEOUT', 5),
    ],

];