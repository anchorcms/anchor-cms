<?php

class Auth {

	private static $session = 'auth';

	public static function guest() {
		return Session::get(static::$session) === null;
	}

	public static function user() {
		if($id = Session::get(static::$session)) {
			return User::find($id);
		}
	}

	public static function attempt($username, $password) {
		$query = User::where('username', '=', $username)
			->where('status', '=', 'active');

		if($user = $query->fetch()) {
			// found a valid user now check the password
			if(password_verify($password, $user->password)) {
				// store user ID in the session
				Session::put(static::$session, $user->id);

				return true;
			}
		}

		return false;
	}

	public static function logout() {
		Session::erase(static::$session);
	}

}