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

class Request {

	public static function ajax() {
		return strcasecmp(array_get($_SERVER, 'HTTP_X_REQUESTED_WITH'), 'XMLHttpRequest') === 0;
	}

	public static function method() {
		return array_get($_SERVER, 'REQUEST_METHOD');
	}

	public static function protocol() {
		return array_get($_SERVER, 'SERVER_PROTOCOL');
	}

}