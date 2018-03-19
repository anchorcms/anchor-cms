<?php

/**
 * Nano
 * Just another php framework
 *
 * @package    nano
 * @link       http://madebykieron.co.uk
 * @copyright  http://unlicense.org/
 */

use System\config;
use System\input;
use System\request;
use System\router;
use System\session;

// Composer
/** @noinspection PhpIncludeInspection */
require PATH . 'vendor/autoload' . EXT;

// Boot the environment
/** @noinspection PhpIncludeInspection */
require SYS . 'boot' . EXT;

// Boot the application
/** @noinspection PhpIncludeInspection */
require APP . 'run' . EXT;

// Set input
Input::detect(Request::method());

// Load session config
Session::setOptions(Config::get('session', []));

// Read session data
Session::start();

// Route the request
/** @noinspection PhpUnhandledExceptionInspection */
$response = Router::create()->dispatch();

// Update session
Session::close();

// Output stuff
$response->send();
