<?php

return array(

	'default' => 'mysql',

	'fetch' => PDO::FETCH_OBJ,
	
	'connections' => array(

		'mysql' => array(
			'driver' => 'mysql',
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => 'bottle',
			'database' => 'anchorcms',
			'charset' => 'utf8'
		)

	)
);