<?php namespace System;

class IoC {

	private static $registry = array(), $instances = array();

	public static function register($name, $resolver, $singleton = false) {
		static::$registry[$name] = array('resolver' => $resolver, 'singleton' => $singleton);
	}
	
	public static function instance($name, $instance) {
		static::$instances[$name] = $instance;
	}

	public static function resolve($name) {
		if(isset(static::$instances[$name])) {
			return static::$instances[$name];
		}
		
		if(isset(static::$registry[$name])) {
			$object = call_user_func(static::$registry[$name]['resolver']);
			
			if(isset(static::$registry[$name]['singleton']) and static::$registry[$name]['singleton']) {
				static::$instances[$name] = $object;
			}

			return $object;
		}
		
		throw new Exception('Nothing registered with ' . $name);
	}

}