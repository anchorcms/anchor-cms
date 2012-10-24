<?php

return array(

	'default' => 'mysql',

	'fetch' => PDO::FETCH_OBJ,

	'connections' => array(

		'mysql' => array(
			'driver' => 'mysql',
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '',
			'database' => 'anchorcms',
			'charset' => 'utf8'
		)

	)
);