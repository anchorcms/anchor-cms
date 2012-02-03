<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Set the error reporting level.
*/
error_reporting(E_ALL | E_STRICT);

/*
	Turn off display error as
	we will manage them manually
*/
ini_set('display_errors', true);

/*
	Include files we are going 
	to need for EVERY request.
*/

// @todo list files manually and remove this dirty hack
foreach(glob(PATH . 'system/classes/*.php') as $file) {
	require $file;
}

// register the auto loader
Autoloader::register();

// Register the default timezone for the application.
//date_default_timezone_set(Config::get('application.timezone'));

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

// now we have the basics down let run our application
require PATH . 'system/run.php';
