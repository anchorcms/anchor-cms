<?php

return array(
	'default' => 'mysql',
	'prefix' => '{{prefix}}',
	'connections' => array(
		'mysql' => array(
			'driver' => 'mysql',
			'hostname' => '{{hostname}}',
			'port' => '{{port}}',
			'username' => '{{username}}',
			'password' => '{{password}}',
			'database' => '{{database}}',
			'charset' => 'utf8'
		)
	)
);
