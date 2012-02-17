<?php

// report all errors
error_reporting(E_ALL);

// show all error uncaught
ini_set('display_errors', true);

/*
	Define some paths and get current config
*/
define('IN_CMS', true);
define('PATH', pathinfo(dirname(__FILE__), PATHINFO_DIRNAME) . '/');

/*
	Helper functions
*/
function random($length = 16) {
	$pool = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 1);
	$value = '';

	for ($i = 0; $i < $length; $i++)  {
		$value .= $pool[mt_rand(0, 61)];
	}

	return $value;
}

/*
	Include some files.
*/
require PATH . 'system/classes/autoload.php';
require PATH . 'system/classes/helpers.php';

// map classes
Autoloader::map(array(
	'Schema' => PATH . 'upgrade/classes/schema.php',
	'Migrations' => PATH . 'upgrade/classes/migrations.php'
));

// tell the autoloader where to find classes
Autoloader::directory(array(
	PATH . 'system/classes/'
));

// register the auto loader
Autoloader::register();

/**
	Report all errors let our error class decide which to display
*/
error_reporting(-1);

/**
	Error display will be handled by our error class
*/
ini_safe_set('display_errors', false);

// Register the default timezone for the application.
date_default_timezone_set(Config::get('application.timezone'));

// Register the PHP exception handler.
set_exception_handler(array('Error', 'exception'));

// Register the PHP error handler.
set_error_handler(array('Error', 'native'));

// Register the shutdown handler.
register_shutdown_function(array('Error', 'shutdown'));

// load current config file
Config::load(PATH . 'config.php');

// add and apply migrations
require PATH . 'upgrade/migrations.php';

// write any config changes
Config::write(PATH . 'config.php', Config::get());

// redirect
header('Location: complete.php');