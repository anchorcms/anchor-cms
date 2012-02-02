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

		//  The default theme can accept a Typekit account and use their fonts.
		//  When you get given the script tags to embed, like so:
		//
		//  <script type="text/javascript" src="http://use.typekit.com/pfa5tzi.js"></script>
		//  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		//
		//  Extract the random string of text before the ".js", and put it here. (optional, of course)
		'typekit' => '',

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
