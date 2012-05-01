<?php defined('IN_CMS') or die('No direct access allowed.');

class Log {

	public static function write($severity, $message) {
		if(Config::get('error.log') === false) {
			return;
		}

		$line = '[' . $severity . '] --> ' . $message . PHP_EOL;

		if($fp = @fopen(PATH . 'system/logs/' . date("Y-m-d") . '.log', 'a+')) {
			fwrite($fp, $line);
			fclose($fp);
		}
	}

	public static function __callStatic($severity, $parameters) {
		static::write($severity, $parameters[0]);
	}

	public static function exception($e) {
		static::write('error', static::format($e));
	}

	private static function format($e) {
		return $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
	}

}