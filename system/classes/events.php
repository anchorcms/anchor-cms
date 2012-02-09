<?php defined('IN_CMS') or die('No direct access allowed.');

class Events {

	private static $stack = array();

	public static function bind($page, $fn, $area = 'main') {
		if(!isset(static::$stack[$page])) {
			static::$stack[$page] = array();
		}
		static::$stack[$page][$area] = $fn;
	}

	public static function call($area, $default = '') {
		$page = base_name(Request::uri());
		return isset(static::$stack[$page][$area]) and is_callable(static::$stack[$page][$area]) ? static::$stack[$page][$area]() : $default;
	}

}