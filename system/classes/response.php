<?php defined('IN_CMS') or die('No direct access allowed.');

class Response {
	
	private static $contents = '';
	private static $headers = array();
	
	public static function header($str) {
		static::$headers[] = $str;
	}
	
	public static function append($str) {
		static::$contents .= $str;
	}
	
	public static function error($code = 500) {
		switch($code) {
			case 404:
				Template::render(404);
		}
	}
	
	public static function send() {
		foreach(static::$headers as $header) {
			header($header);
		}
		echo static::$contents;
	}
	
	public static function redirect($url) {
		static::header('Location: /' . $url);
		static::$contents = '';
	}
	
}
