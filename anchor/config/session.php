<?php

return array(
	/*
	 * Session driver (pdo, memcached, memcache)
	 */
	'driver' => 'pdo',

	/*
	 * Session cookie name
	 */
	'cookie' => 'anchor',

	/*
	 * Session database table name when used with the 'database' driver
	 */
	'table' => 'sessions',

	/*
	 * Memcache Servers
	 */
	'servers' => array(
		array(
			'host' => '127.0.0.1',
			'port' => 11211,
			'weight' => 10
		),
	),

	/*
	 * Session directory
	 */
	'dir' => dirname(__DIR__) . '/sessions',

	/*
	 * Session lifespan in seconds
	 */
	'lifetime' => 14400,

	/*
	 * Session cookie expires at the end of the browser session
	 */
	'expire_on_close' => false,

	/*
	 * Session cookie path
	 */
	'path' => '/',

	/*
	 * Session cookie domain
	 */
	'domain' => '',

	/*
	 * Session cookie secure (only available via https)
	 */
	'secure' => false,

	/*
	 * Accessible only through the HTTP protocol
	 */
	'httponly' => false
);