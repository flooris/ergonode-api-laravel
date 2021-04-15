<?php

return [
    'hostname' => env('ERGONODE_HOSTNAME', ''),
    'username' => env('ERGONODE_USERNAME', ''),
    'password' => env('ERGONODE_PASSWORD', ''),
    'client-options' => [
        'user-agent' => 'flooris/ergonode-api'
    ]
];
