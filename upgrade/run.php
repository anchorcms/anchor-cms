<?php

/**
	Basic upgrade process
*/

// Set the include path
define('PATH', pathinfo(dirname(__FILE__), PATHINFO_DIRNAME) . '/');

// Block direct access to any PHP files
define('IN_CMS', true);

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
	@ini_set('display_errors', true);
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
	Run the upgrade
*/
if(Db::row("show columns from users like 'email'") === false) {
	$sql = "ALTER TABLE `users` ADD `email` VARCHAR( 140 ) NOT NULL AFTER `password` ;";
	Db::exec($sql);
}

/**
	Redirect to complete page
*/
Response::header('Location', 'complete.php');

// go
Response::send();	
