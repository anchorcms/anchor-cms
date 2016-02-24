<?php

/*
 * Set your applications current timezone
 */
date_default_timezone_set(Config::app('timezone', 'UTC'));

/*
 * Define the application error reporting level based on your environment
 */
switch(constant('ENV')) {
	case 'dev':
	case 'development':
	case 'local':
	case 'localhost':
		ini_set('display_errors', true);
		error_reporting(-1);
		break;

	default:
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

/*
 * Set autoload directories to include your app models and libraries
 */
Autoloader::directory(array(
	APP . 'models',
	APP . 'libraries'
));

/**
 * Helpers
 */
require APP . 'helpers' . EXT;

/**
 * Anchor setup
 */
try {
	Anchor::setup();
} catch(Exception $e) {

	if(ini_get('display_errors') != "1") {
		echo "<h1>Something went wrong, please notify the owner of the site</h1>";
	} else {
		Error::exception($e);
	}

	Error::log($e);
	die();
}


/**
 * Import defined routes
 */
if(is_admin()) {
	// Set posts per page for admin
	Config::set('admin.posts_per_page', 6);

	require APP . 'routes/admin' . EXT;
	require APP . 'routes/categories' . EXT;
	require APP . 'routes/comments' . EXT;
	require APP . 'routes/fields' . EXT;
	require APP . 'routes/menu' . EXT;
	require APP . 'routes/metadata' . EXT;
	require APP . 'routes/pages' . EXT;
	require APP . 'routes/panel' . EXT;
	require APP . 'routes/plugins' . EXT;
	require APP . 'routes/posts' . EXT;
	require APP . 'routes/users' . EXT;
	require APP . 'routes/variables' . EXT;
	require APP . 'routes/pagetypes' . EXT;
}
else {
	require APP . 'routes/site' . EXT;
}
