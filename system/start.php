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
 * Boot the environment
 */
require PATH . 'system/boot' . EXT;

/**
 * Boot the application
 */
if(is_readable($run = APP . 'run' . EXT)) {
	require $run;
}

/**
 * Set input
 */
Input::detect(Request::method());

/**
 * Read session data
 */
Session::read();

/**
 * Import defined routes
 */
Router::import(Uri::current());

/**
 * Route the request
 */
$response = Router::create(Request::method(), Uri::current())->dispatch();

/**
 * Update session
 */
Session::write();

/**
 * Output stuff
 */
$response->send();