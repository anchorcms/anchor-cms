<?php

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

/**
 * Composer
 */
require PATH . 'vendor/autoload' . EXT;

/**
 * Boot the environment
 */
require SYS . 'boot' . EXT;

/**
 * Boot the application
 */
require APP . 'run' . EXT;

/**
 * Set input
 */
Input::detect(Request::method());

/**
 * Load session config
 */
Session::setOptions(Config::get('session'));

/**
 * Read session data
 */
Session::start();

/**
 * Route the request
 */
$response = Router::create()->dispatch();

/**
 * Update session
 */
Session::close();

/**
 * Output stuff
 */
$response->send();
