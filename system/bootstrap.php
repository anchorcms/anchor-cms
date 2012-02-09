<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Set the error reporting level.
	Turn off error reporting for production setups
*/
error_reporting(0);

/*
	Helper for setting php settings
*/
function ini_safe_set($key, $value) {
	// some hosts disable ini_set for security 
	// lets check so see if its disabled
	if(($disable_functions = ini_get('disable_functions')) !== false) {
		// if it is disabled then return as there is nothing we can do
		if(strpos($disable_functions, 'ini_set') !== false) {
			return false;
		}
	}

	// lets set it because we can!
	return ini_set($key, $value);
}

/*
	Hide errors that are not caught
*/
ini_safe_set('display_errors', false);

/*
	Check our environment
*/
if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	// echo and exit with some usful information
	echo 'Anchor requires PHP 5.3 or newer, your current environment is running PHP ' . PHP_VERSION;
	exit(1);
}

/*
	Disable magic quotes
	note: magic quotes is deprecated in PHP 5.3
	src: php.net/manual/en/security.magicquotes.disabling.php
*/
if(function_exists('get_magic_quotes_gpc')) {
	if(get_magic_quotes_gpc()) {
		ini_safe_set('magic_quotes_gpc', false);
		ini_safe_set('magic_quotes_runtime', false);
		ini_safe_set('magic_quotes_sybase', false);
	}
}

/*
	Include files we are going 
	to need for every request.
*/
require PATH . 'system/classes/autoload.php';
require PATH . 'system/classes/config.php';
require PATH . 'system/classes/error.php';

// register the auto loader
Autoloader::register();

/*
	Check our installation
*/
if(Config::load() === false) {
	// looks like we are missing a config file
	echo file_get_contents(PATH . 'system/admin/theme/error_config.php');
	exit(1);
}

/*
	Debug options
	Running with debug enabled should report all errors
*/
if(Config::get('debug')) {
	// report all errors
	error_reporting(E_ALL);
	
	// show all error uncaught
	ini_safe_set('display_errors', true);
}

// Register the default timezone for the application.
date_default_timezone_set(Config::get('application.timezone'));

// Register the PHP exception handler.
set_exception_handler(array('Error', 'exception'));

// Register the PHP error handler.
set_error_handler(array('Error', 'native'));

// Register the shutdown handler.
register_shutdown_function(array('Error', 'shutdown'));

/*
	Start session handler
*/
Session::start();

/*
	Handle routing
*/
Anchor::run();

/*
	Output awesomeness!
*/
Response::send();

/*
	Close and end session
*/
Session::end();
