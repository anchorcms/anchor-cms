<?php

return [
    'default'     => 'mysql',
    'prefix'      => '{{prefix}}',
    'connections' => [
        'mysql' => [
            'driver'   => 'mysql',
            'hostname' => '{{hostname}}',
            'port'     => '{{port}}',
            'username' => '{{username}}',
            'password' => '{{password}}',
            'database' => '{{database}}',
            'charset'  => 'utf8'
        ]
    ]
];
