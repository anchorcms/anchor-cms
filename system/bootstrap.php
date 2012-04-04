<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Check our environment
*/
if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	// echo and exit with some usful information
	echo 'Anchor requires PHP 5.3 or newer, your current environment is running PHP ' . PHP_VERSION;
	exit(1);
}

/**
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

/**
	Magic Quotes Fix
	note: magic quotes is deprecated in PHP 5.3
	src: php.net/manual/en/security.magicquotes.disabling.php
*/
$magic_quotes = function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc();
$magic_quotes_sybase = ini_get('magic_quotes_sybase') and strtolower(ini_get('magic_quotes_sybase')) != 'off';

if($magic_quotes or $magic_quotes_sybase) {
	function strip_slashes_recursive($data) {
		if(is_array($data)) {
			foreach ($data as $key => $value) {
				$data[strip_slashes_recursive($key)] = strip_slashes_recursive($value);
			}
		} else {
			$data = stripslashes($data);
		}
		return $data;
	}
	$_GET = strip_slashes_recursive($_GET);
	$_POST = strip_slashes_recursive($_POST);
	$_COOKIE = strip_slashes_recursive($_COOKIE);
	$_REQUEST = strip_slashes_recursive($_REQUEST);
}

// Windows IIS Compatibility  
if(!isset($_SERVER['REQUEST_URI'])) { 
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1); 
	
	if(isset($_SERVER['QUERY_STRING'])) { 
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
	} 
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