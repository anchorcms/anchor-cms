<?php defined('IN_CMS') or die('No direct access allowed.');

class Email {

	private static function headers($headers) {
		$str = '';
		
		foreach($headers as $key => $value) {
			$str .= $key . ': ' . $value . PHP_EOL;
		}
		
		return $str;
	}

	public static function send($to, $subject, $message, $headers = array()) {
		return mail($to, $subject, $message, static::headers($headers));
	}

}
