<?php

return [
    'default'     => 'mysql',
    'prefix'      => '{{prefix}}',
    'connections' => [
        'mysql' => [
            'driver'   => '{{driver}}',
            'hostname' => '{{hostname}}',
            'port'     => '{{port}}',
            'username' => '{{username}}',
            'password' => '{{password}}',
            'database' => '{{database}}',
            'charset'  => '{{charset}}'
        ]
    ]
];
