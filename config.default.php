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
		'expire' => 3600, // 1 hour
		'path' => '/',
		'domain' => ''
	),

	// Set debugging options.
	// Used for development only. I'd turn this off the live site.
	'debug' => false
);
