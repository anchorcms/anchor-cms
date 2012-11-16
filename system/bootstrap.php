<?php namespace System;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

/*
	Include application helpers
*/
require PATH . 'system/helpers' . EXT;

/*
	Register Globals Fix
*/
if(ini_get('register_globals')) {
	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset(${$key});
		}
	}
}

/*
	Magic Quotes Fix
	note: magic quotes is deprecated in PHP 5.3
	src: php.net/manual/en/security.magicquotes.disabling.php
*/
if(magic_quotes()) {
	$magics = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);

	foreach($magics as &$magic) {
		$magic = array_strip_slashes($magic);
	}
}

// get our autoloader
require PATH . 'system/error' . EXT;
require PATH . 'system/config' . EXT;
require PATH . 'system/autoload' . EXT;

// register auto loader
spl_autoload_register(array('System\\Autoloader', 'load'));

// map namespace
Autoloader::namespaces(array(
	'System' => SYS));

// The auto-loader can search directories for files using the PSR-0
// naming convention.
Autoloader::directories(array(
	APP . 'models',
	APP . 'libraries'
));

// Aliases allow you to use classes without always specifying their
// fully namespaced path.
Autoloader::$aliases = Config::get('aliases');

/*
	Error handling
*/
set_exception_handler(function($e) {
	Error::exception($e);
});

set_error_handler(function($code, $error, $file, $line) {
	Error::native($code, $error, $file, $line);
});

register_shutdown_function(function() {
	Error::shutdown();
});

// report all errors
error_reporting(-1);

/*
	Localisation - Register the default timezone for the application.
*/
date_default_timezone_set(Config::get('application.timezone', 'UTC'));

/*
	Set input
*/
switch(Request::method()) {
	case 'GET':
		parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), Input::$array);
		break;

	case 'POST':
		Input::$array = $_POST;
		break;

	default:
		parse_str(file_get_contents('php://input'), Input::$array);
}

/*
	Start application
*/
if(is_readable($start = APP . 'start' . EXT)) require $start;

Session::load();

/*
	Route the request
*/

// load defined routes
Router::load();

// call requested route
$response = Router::route(Request::method(), Uri::current())->call();

// Persist The Session To Storage
Session::save();

// And We're Done!
$response->send();