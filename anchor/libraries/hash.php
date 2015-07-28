<?php

class Hash {

	public static function make($value, $rounds = 10) {
		// format number of rounds
		if($rounds > 31 or $rounds < 4) {
			throw new OutOfRangeException('Blowfish iteration count must be between 4 and 31');
		}
		if (function_exists('password_hash')) {
			// If PHP 5.5+ use the native password_* API instead
			return password_hash($value, PASSWORD_DEFAULT, array('COST' => $rounds));
		}

		$rounds = sprintf('%02d', $rounds);

		// blowfish salt is 22 characters from the alphabet "./0-9A-Za-z".
		$salt = Security::randomString(22, './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

		return crypt($value, '$2a$' . $rounds . '$' . $salt);
	}

	public static function check($value, $hash) {
		if (function_exists('password_verify')) {
			// If PHP 5.5+ use the native password_* API instead
			return password_verify($value, $hash);
		}
		return Security::hashEquals(
			crypt($value, $hash),
			$hash
		);
	}

}
