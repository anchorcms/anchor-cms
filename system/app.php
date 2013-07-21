<?php namespace System;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

class App {

	private $resolvers = array();

	private $instances = array();

	public function register($name, $resolver, $singleton = false) {
		$this->resolvers[$name] = compact('resolver', 'singleton');
	}

	public function resolve($name) {
		if( ! isset($this->resolvers[$name])) {
			return false;
		}

		list($resolver, $singleton) = $this->resolvers[$name]);

		if($singleton) {
			if(isset($this->instances[$name])) {
				return $this->instances[$name];
			}

			return $this->instances[$name] = call_user_func($resolver);
		}

		return call_user_func($resolver);
	}

}