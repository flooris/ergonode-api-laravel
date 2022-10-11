<?php

return [
    'hostname'       => env('ERGONODE_HOSTNAME', ''),
    'username'       => env('ERGONODE_USERNAME', ''),
    'password'       => env('ERGONODE_PASSWORD', ''),
    'locale'         => env('ERGONODE_LOCALE', "nl_NL"),
    'client-options' => [
        'user-agent' => 'flooris/ergonode-api',
    ],
];
