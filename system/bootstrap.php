<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Include application helpers
*/
require PATH . 'system/core/helpers.php';

/**
 *	Check our environment
 */
if(has_php(5.3) === false) {
	// echo and exit with some usful information
	echo 'Anchor requires PHP 5.3 or newer, your current environment is running PHP ' . PHP_VERSION;
	exit(1);
}

/**
	Register Globals Fix
*/
if(ini_get('register_globals')) {

	$globals = array();
	if (isset($_SESSION)) $globals[] = $_SESSION;
	if (isset($_REQUEST)) $globals[] = $_REQUEST;
	if (isset($_SERVER)) $globals[] = $_SERVER;
	if (isset($_FILES)) $globals[] = $_FILES;

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
if(magic_quotes()) {
	$magics = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);

	foreach($magics as &$magic) {
		$magic = array_strip_slashes($magic);
	}
}

// get our autoloader
require PATH . 'system/core/autoload.php';

// tell the autoloader where to find classes
Autoloader::directory(array(
	PATH . 'system/core/',
	PATH . 'system/library/'
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

// set locale
if(setlocale(LC_ALL, Config::get('application.language') . '.utf8') === false) {
	Log::warning('setlocate failed, please check your system has ' . Config::get('application.language') . ' installed.');
}

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