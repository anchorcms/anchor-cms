<?php

class Csrf {

	public static function token() {
		// load tokens from session
		$tokens = Session::get('tokens', array());

		// add new token
		$tokens[] = ($token = Str::random(40));

		// save tokens in sesson
		Session::set('tokens', $tokens);

		return $token;
	}

	public static function verify($token) {
		// load tokens from session
		$tokens = Session::get('tokens', array());

		if(($index = array_search($token, $tokens)) !== false) {
			// remove token when used
			unset($tokens[$index]);

			// update tokens
			Session::set('tokens', $tokens);

			return true;
		}

		return false;
	}

}