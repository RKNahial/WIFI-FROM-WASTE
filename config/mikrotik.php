<?php

return [
    'host' => env('MIKROTIK_HOST', '192.168.88.1'),
    'username' => env('MIKROTIK_USERNAME', 'admin'),
    'password' => env('MIKROTIK_PASSWORD', ''),
    'port' => env('MIKROTIK_PORT', 8728),
    'interface' => env('MIKROTIK_INTERFACE', 'ether1'),
];