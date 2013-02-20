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
 * Route the request
 */
$response = Router::create()->match(Request::method(), Uri::current())->run();

/**
 * Update session
 */
Session::write();

/**
 * Output stuff
 */
$response->send();