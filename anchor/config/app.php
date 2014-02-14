<?php

return array(
	/**
	 * The public base url where anchor is installed
	 */
	'url' => '/',

	/**
	 * The application entry point if clean urls are not available
	 */
	'index' => 'index.php',

	/**
	 * Your timezone
	 */
	'timezone' => 'UTC',

	/**
	 * Your application key
	 */
	'key' => 'ChangeMe!',

	/**
	 * Your language
	 */
	'language' => 'en_GB',

	/**
	 * Your application encoding
	 */
	'encoding' => 'UTF-8',

	/**
	 * The application providers
	 */
	'providers' => array(
		'\\Ship\\Http\\Provider',
		'\\Ship\\Routing\\Provider',
		'\\Ship\\Database\\Provider',
		'\\Ship\\Database\\Query\\Provider',
		'\\Ship\\Session\\Provider',
		'\\Anchor\\Providers\\ShipServiceProvider'
	)
);