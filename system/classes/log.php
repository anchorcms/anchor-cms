<?php defined('IN_CMS') or die('No direct access allowed.');

class Log {

	public static function write($severity, $message) {
		// skip logging for production mode
		if(!Config::get('debug')) return;

		$line = '[' . $severity . '] --> ' . $message . PHP_EOL;

		if($fp = @fopen(PATH . 'system/logs/' . date("Y-m-d") . '.log', 'a+')) {
			fwrite($fp, $line);
			fclose($fp);
		}
	}

	public static function __callStatic($severity, $parameters) {
		static::write($severity, current($parameters));
	}

}