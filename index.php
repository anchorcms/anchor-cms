<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @visualidiot, with thanks to @kieronwilson, @spenserj and a bunch of other contributors.
 *    You're all great.
 */

//  Set the include path
define('PATH', __DIR__ . '/');

// Set the base url path
call_user_func(function() {
	// relative script path
	$path = $_SERVER['SCRIPT_NAME'];
	
	// check if we are using clean urls
	$url = file_exists(PATH . '.htaccess') ? trim($path, basename(__FILE__)) : $path . '/';

	define('URL_PATH', $url);
});

//  Block direct access to any PHP files
define('IN_CMS', true);

//  Anchor version
define('ANCHOR_VERSION', 0.4);

// Lets bootstrap our application and get it ready to run
require PATH . 'system/bootstrap.php';
