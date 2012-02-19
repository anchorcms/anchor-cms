<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Anchor Cms - Default configuration
*/
return array(
	//  MySQL database details
	'database' => array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'name' => 'anchorcms'
	),
	
	// Application settings
	'application' => array(
		// url paths
		'base_url' => '/',
		'index_page' => 'index.php',

		// your time zone
		'timezone' => 'UTC',

		// access to admin
		'admin_folder' => 'admin',

		// your unique application key used for signing passwords
		'key' => ''
	),
	
	// Session details
	'session' => array(
		'name' => 'anchorcms',
		'expire' => 3600,
		'path' => '/',
		'domain' => ''
	),

	// Error handling
	'error' => array(
		'ignore' => array(E_NOTICE, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED),
		'detail' => false,
		'log' => false
	),

	// Show database profile
	'debug' => false
);
