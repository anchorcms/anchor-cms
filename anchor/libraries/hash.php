<?php

class Hash {

	public static function make($value, $rounds = 12) {
		return password_hash($value, PASSWORD_BCRYPT, array('cost' => $rounds));
	}

	public static function check($value, $hash) {
		return password_verify($value, $hash);
	}

}
