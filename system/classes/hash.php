<?php defined('IN_CMS') or die('No direct access allowed.');

class Hash {

	public static function make($value, $rounds = 8) {
		$work = str_pad($rounds, 2, '0', STR_PAD_LEFT);

		// Bcrypt expects the salt to be 22 base64 encoded characters including
		// dots and slashes. We will get rid of the plus signs included in the
		// base64 data and replace them with dots.
		if (function_exists('openssl_random_pseudo_bytes')) {
			$salt = openssl_random_pseudo_bytes(16);
		} else {
			$salt = Str::random(40);
		}

		$salt = substr(strtr(base64_encode($salt), '+', '.'), 0 , 22);

		return crypt($value, '$2a$'.$work.'$'.$salt);
	}

	public static function check($value, $hash) {
		return crypt($value, $hash) === $hash;
	}
	
}