<?php defined('IN_CMS') or die('No direct access allowed.');

class Log {

	public static function write($severity, $message) {
		$line = '[' . $severity . '] --> ' . $message . PHP_EOL;

		if($fp = @fopen(PATH . 'system/logs/' . date("Y-m-d") . '.log', 'w+')) {
			fwrite($fp, $line);
			fclose($fp);
		}
	}

}