<?php

return array(
	/*
	 * Session driver
	 *
	 * Available options are: cookie, database, memcache, memcached, runtime
	 */
	'driver' => 'database',

	/*
	 * Session cookie name
	 */
	'cookie' => 'anchor',

	/*
	 * Session database table name when used with the 'database' driver
	 */
	'table' => 'sessions',

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
	'secure' => false
);