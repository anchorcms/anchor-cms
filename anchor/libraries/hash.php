<?php

class Hash {

	public static function make($value, $rounds = 10) {
		// format number of rounds
		if($rounds > 31 or $rounds < 4) {
			throw new OutOfRangeException('Blowfish iteration count must be between 4 and 31');
		}

		$rounds = sprintf('%02d', $rounds);

		// blowfish salt is 22 characters from the alphabet "./0-9A-Za-z".
		$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$salt = substr(str_shuffle(str_repeat($pool, 5)), 0, 22);

		return crypt($value, '$2a$' . $rounds . '$' . $salt);
	}

	public static function check($value, $hash) {
		return crypt($value, $hash) === $hash;
	}

}