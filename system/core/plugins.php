<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Plugin API
*/
class Plugins {
	public static $hooks = array();
	public static $plugins = array();
	public static $files = array();

	private static $currentFile = '';

	public static function load($directory=false, $incdir=false) {
		if (!$directory) $directory = PATH . "plugins";
		if (!$incdir) $incdir = $directory;
		$cwd = getcwd();
		chdir($incdir);
		foreach (glob($directory . "/*") as $file) {
			self::$currentFile = $file;
			self::$plugins[] = $file;
			include $file;
		}
		chdir($cwd);
	}

	public static function add_hook($name, $type, $hook) {
		if (!isset(self::$hooks[$type])) self::$hooks[$type] = array();
		self::$hooks[$type][$name] = $hook;
		self::$files[$name] = self::$currentFile;
		return $name;
	}

	public static function remove_hook($name, $type=false) {
		if (!$type) {
			$type = self::get_type_of_hook_name($name);
			if (!$type) return false;
		}
		if (!isset(self::$hooks[$type]) || !isset(self::$hooks[$type][$name])) return false;
		unset(self::$hooks[$type][$name]);
		unset(self::$files[$name]);
		return true;
	}

	public static function get_hook_with_name($name, $type=false) {
		if (!$type) {
			$type = self::get_type_of_hook_name($name);
			if (!$type) return false;
		}
		return self::$hooks[$type][$name];
	}

	public static function get_hooks_by_type($type) {
		if (!isset(self::$hooks[$type])) return false;
		return self::$hooks[$type];
	}

	public static function get_hooks() {
		return self::$hooks;
	}

	public static function call_hooks($type, $data=false, $false_responses=true) {
		$hooks = self::get_hooks_by_type($type);
		if (!$hooks) return false;
		foreach ($hooks as $name => $hook) {
			// $response = $hook($data);
			if (is_array($hook)) {
				$class = new $hook[0];
				$response = $class->$hook[1]($data);
			} else {
				$response = $hook($data);
			}
			$data = ($hook === false || $hook === null) || $false_responses ? $response : $data;
		}
		return $data;
	}

	private static function get_type_of_hook_name($name) {
		foreach (self::$hooks as $type => $hooks) {
			if (isset($hooks[$name])) return $type;
		}
		return false;
	}

	/**
		Plugin Hooks
	*/

	public static function plugin_hook_before_header() {
		self::call_hooks('before_header');
	}

	public static function plugin_hook_after_footer() {
		self::call_hooks('after_footer');
	}

	public static function plugin_hook_retrieve_post($post) {
		$newpost = self::call_hooks('retrieve_post', $post);
		return $newpost ? $newpost : $post;
	}

	public static function plugin_hook_retrieve_post_not_in_admin($post) {
		if (defined('IN_ADMIN') && IN_ADMIN) return $post;
		$newpost = self::call_hooks('retrieve_post_not_in_admin', $post);
		return $newpost ? $newpost : $post;
	}

	public static function plugin_hook_retrieve_post_in_admin($post) {
		if (!defined('IN_ADMIN') || !IN_ADMIN) return $post;
		$newpost = self::call_hooks('retrieve_post_in_admin', $post);
		return $newpost ? $newpost : $post;
	}
}
