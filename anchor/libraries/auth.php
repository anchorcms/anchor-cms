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
		if($user = User::where('username', '=', $username)->where('status', '=', 'active')->fetch()) {
			// found a valid user now check the password
			if(Hash::check($password, $user->password)) {
				// store user ID in the session
				Session::put(static::$session, $user->id);

				return true;
			}
		}
        Session::incrementAttempts();

		return false;
	}

    public static function incrementAttempts()
    {
        Session::incrementAttempts();
    }

    public static function reachedMaxAttempts()
    {
        return Session::reachedMaxAttempts();
    }

    public static function resetAttempts()
    {
        Session::resetAttempts();
    }

    public static function canAttempt()
    {
        return Session::canAttempt();
    }

    public static function getMaxAttemptsTimeout()
    {
        return Session::getMaxAttemptsTimeout();
    }

	public static function logout() {
		Session::erase(static::$session);
	}

}