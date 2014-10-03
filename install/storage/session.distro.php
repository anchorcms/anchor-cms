<?php

return array(
	'driver' => 'database',
	'cookie' => 'anchorcms',
	'table' => '{{table}}',
	'lifetime' => 86400,
	'expire_on_close' => false,
	'path' => '/',
	'domain' => '',
	'secure' => false,
    'max_attempts' => 4,
    'max_attempts_timeout' => 1 // minutes
);