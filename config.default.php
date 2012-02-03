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

	// Site information
	'metadata' => array(
		'sitename' => 'My First Anchor Site',
		'description' => 'This is my very cool Anchor CMS site, written in PHP5 and MySQL.',

		//  You can use whatever you want here.
		'twitter' => '',
		'date_format' => 'jS M, Y'
	),

	// Set the theme
	'theme' => 'default',

	// Set debugging options.
	// Used for development only. I'd turn this off the live site.
	'debug' => false
);
