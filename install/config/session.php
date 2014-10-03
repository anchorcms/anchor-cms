<?php

return array(
	'driver' => 'cookie',
	'cookie' => 'anchorcms-install',
	'table' => 'sessions',
	'lifetime' => 14400,
	'expire_on_close' => true,
	'path' => '/',
	'domain' => '',
	'secure' => false,
    'max_attempts' => 4,
    'max_attempts_timeout' => 1 // minutes
);