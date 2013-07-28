<?php

/*
 * Set your applications current timezone
 */
if( ! date_default_timezone_get()) {
	date_default_timezone_set(Config::app('timezone'));
}

/*
 * Define the application error reporting level based on your environment
 */
switch(constant('ENV')) {
	case 'dev':
		ini_set('display_errors', true);
		error_reporting(-1);
		break;

	default:
		ini_set('display_errors', true);
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

/*
 * Set autoload directories to include your app models and libraries
 */
Autoloader::directory(array(
	APP . 'models',
	APP . 'libraries',
	PATH . 'anchor/libraries'
));

/**
 * Password compat
 */
require PATH . 'anchor/libraries/password' . EXT;

/**
 * Set the current uri from get
 */
if($route = Arr::get($_GET, 'route', '/')) {
	Uri::$current = trim($route, '/') ?: '/';
}

/**
 * Import defined routes
 */
require APP . 'routes' . EXT;