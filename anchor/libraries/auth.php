<?php

class Auth {

	private static $session = 'auth';

	public static function guest() {
		return Session::get(static::$session) === null;
	}

	public static function user() {
		return Session::get(static::$session);
	}

	public static function attempt($username, $password) {
		if($user = User::search(array('username' => $username))) {
			if(Hash::check($password, $user->password)) {
				Session::put(static::$session, $user);

				return true;
			}
		}

		return false;
	}

	public static function logout() {
		Session::erase(static::$session);
	}

}