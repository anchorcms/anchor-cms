<?php

return array(
	'default' => 'sqlite',
	'prefix' => '{{prefix}}',
	'connections' => array(
		'sqlite' => array(
			'driver' => 'sqlite',
			'database' => '{{path}}',
			'charset' => 'utf8'
		)
	)
);
