<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Set the error reporting level.
*/
error_reporting(E_ALL);

/*
	Show errors that are not caught
	note: some hosts disable ini_set for security reasons so we will silence the output of any errors
*/
@ini_set('display_errors', true);

/*
	Check our environment
*/
if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	// echo and exit with some usful information
	echo 'Anchor requires PHP 5.3 or newer, your current environment is running PHP ' . PHP_VERSION;
	exit(1);
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
