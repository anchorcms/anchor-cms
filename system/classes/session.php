<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	This will handle our native sessions for now 
	but provides somes flexibility in the future 
	if we decide to use other methods for 
	session management
*/

class Session {
	
	public static function start() {
		session_start();
	}
	
	public static function end() {
		session_write_close();
	}
	
	public static function get($key, $default = false) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
	}
	
	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public static function forget($key) {
		if(isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}
	
}
