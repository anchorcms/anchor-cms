<?php

return array(
	/**
	 * The default database configuration
	 */
	'default' => 'sqlite',

	/**
	 * Database connection definitions
	 */
	'connections' => array(
		/**
		 * Sqlite
		 */
		'sqlite' => array(
			'driver' => 'sqlite',
			'dbname' => '/Users/idiot/Sites/anchor/anchor/db/anchor.sqlite'
		),

		/**
		 * PostgreSQL
		 */
		'pgsql' => array(
			'driver' => 'pgsql',
			'host' => 'localhost',
			'port' => '5432',
			'dbname' => 'anchor',
			'user' => 'root',
			'pass' => '',
			'charset' => '',
			'schema' => '',
		),

		/**
		 * MySQL
		 */
		'mysql' => array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'port' => '3306',
			'dbname' => 'anchor',
			'user' => 'root',
			'pass' => '',
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
		)
	)
);