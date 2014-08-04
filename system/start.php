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
 * Read session data
 */
Session::read();

/**
 * Route the request
 */
$response = Router::create()->dispatch();

/**
 * Update session
 */
Session::write();

/**
 * Output stuff
 */
$response->send();
