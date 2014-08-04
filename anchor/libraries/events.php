<?php

class Events {

	private static $stack = array();

	private static function parse($page) {
		$name = 'main';

		if(strpos($page, '.') !== false) {
			list($page, $name) = explode('.', $page);
		}

		return array($page, $name);
	}

	public static function bind($page, $fn) {
		list($page, $name) = static::parse($page);

		if(!isset(static::$stack[$page])) {
			static::$stack[$page] = array();
		}

		static::$stack[$page][$name] = $fn;
	}

	public static function call($name = '') {
		$page = Registry::get('page');

		if(empty($name)) {
			$name = 'main';
		}

		if($func = isset(static::$stack[$page->slug][$name]) ? static::$stack[$page->slug][$name] : false) {
			return is_callable($func) ? $func() : '';
		}

		return '';
	}

}