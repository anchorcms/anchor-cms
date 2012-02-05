<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Set the error reporting level.
*/
error_reporting(E_ALL | E_STRICT);

/*
	Show errors that are not caught
*/
ini_set('display_errors', true);

/*
	Check our environment
*/
if(floatval(PHP_VERSION) < 5.3) {
	// echo and exit with some usful information
	echo 'Anchor requires PHP 5.3 or newer, your current environment is running PHP ' . floatval(PHP_VERSION);
	exit(1);
}

/*
	Include files we are going 
	to need for every request.
*/
require PATH . 'system/classes/autoload.php';
require PATH . 'system/classes/config.php';
require PATH . 'system/classes/ioc.php';

// register the auto loader
Autoloader::register();

/*
	Check our installation
*/
if(Config::load() === false) {
	// looks like we are missing a config file
	echo str_replace('[[base_url]]', URL_PATH, file_get_contents(PATH . 'system/admin/theme/error_config.php'));
	exit(1);
}

// Register the default timezone for the application.
date_default_timezone_set(Config::get('application.timezone'));

// Register the PHP exception handler.
set_exception_handler(function($e) {
	Error::exception($e);
});

// Register the PHP error handler.
set_error_handler(function($code, $error, $file, $line) {
	Error::native($code, $error, $file, $line);
});

// Register the shutdown handler.
register_shutdown_function(function() {
	Error::shutdown();
});

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
