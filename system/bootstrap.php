<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Check our environment
*/
if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	// echo and exit with some usful information
	echo 'Anchor requires PHP 5.3 or newer, your current environment is running PHP ' . PHP_VERSION;
	exit(1);
}

// get our autoloader
require PATH . 'system/classes/helpers.php';
require PATH . 'system/classes/autoload.php';

// directly map classes for super fast loading
Autoloader::map(array(
	'Config' => PATH . 'system/classes/config.php',
	'Error' => PATH . 'system/classes/error.php',
	'Session' => PATH . 'system/classes/session.php',
	'Anchor' => PATH . 'system/classes/anchor.php',
	'Template' => PATH . 'system/classes/template.php',
	'Request' => PATH . 'system/classes/request.php',
	'Response' => PATH . 'system/classes/response.php',
	'Log' => PATH . 'system/classes/log.php',
	'Db' => PATH . 'system/classes/db.php',
	'IoC' => PATH . 'system/classes/ioc.php',
	'Url' => PATH . 'system/classes/url.php'
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

/**
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

/**
	Check our installation
*/
if(Config::load(PATH . 'config.php') === false) {
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

/**
	Start session handler
*/
Session::start();

/**
	Handle routing
*/
Anchor::run();

/**
	Close and end session
*/
Session::end();

/**
	Output awesomeness!
*/
Response::send();