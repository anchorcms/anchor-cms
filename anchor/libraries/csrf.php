<?php

class Csrf {

	public static function check($token) {
		$tokens = Session::get('csrf_tokens', array());

		if(($index = array_search($token, $tokens)) !== false) {
			unset($tokens[$index]);

			Session::put('csrf_tokens', $tokens);

			return $token;
		}

		return false;
	}

	public static function token() {
		$tokens = Session::get('csrf_tokens', array());

		$token = hash('md5', noise());

		$tokens[] = $token;

		Session::put('csrf_tokens', $tokens);

		return $token;
	}

}