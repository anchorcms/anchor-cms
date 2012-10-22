<?php namespace System;

use Session\Payload, System\Cookie;

class Session {

	public static $instance;

	public static function load() {
		static::start(Config::get('session.driver'));

		static::$instance->load(Cookie::get(Config::get('session.cookie')));
	}

	public static function start($driver) {
		static::$instance = new Session\Payload(static::factory($driver));
	}

	public static function factory($driver) {
		switch ($driver) {
			case 'cookie':
				return new Session\Drivers\Cookie;

			case 'database':
				return new Session\Drivers\Database;

			default:
				throw new \Exception("Session driver [$driver] is not supported.");
		}
	}

	public static function started() {
		return ! is_null(static::$instance);
	}

	public static function instance() {
		if(static::started()) return static::$instance;

		throw new \Exception("A driver must be set before using the session.");
	}

	public static function __callStatic($method, $parameters = array()) {
		return call_user_func_array(array(static::instance(), $method), $parameters);
	}

}