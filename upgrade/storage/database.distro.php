<?php

return array(

	'default' => 'mysql',

	'fetch' => PDO::FETCH_OBJ,

	'connections' => array(

		'mysql' => array(
			'driver' => 'mysql',
			'hostname' => 'localhost',
			'port' => 3306,
			'username' => 'root',
			'password' => '',
			'database' => '',
			'charset' => 'utf8'
		)

	)
);