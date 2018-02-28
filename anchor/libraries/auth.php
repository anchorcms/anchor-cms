<?php

use System\session;

/**
 * auth class
 * Provides user authentication methods
 */
class auth
{
    /**
     * Holds the current session key for authentication data
     *
     * @var string
     */
    private static $session = 'auth';

    /**
     * Checks whether the current user is a guest
     *
     * @return bool
     */
    public static function guest()
    {
        return Session::get(static::$session) === null;
    }

    /**
     * Retrieves the current user
     *
     * @return \stdClass
     * @throws \Exception
     */
    public static function user()
    {
        if ($id = Session::get(static::$session)) {
            return User::find($id);
        }
    }

    /**
     * Checks whether the current user is an administrator
     *
     * @return bool
     * @throws \Exception
     */
    public static function admin()
    {
        if ($id = Session::get(static::$session)) {
            return User::find($id)->role == 'administrator';
        }

        return false;
    }

    /**
     * Checks whether a specific user ID matches the current user
     *
     * @param int $id user ID to check
     *
     * @return bool
     */
    public static function me($id)
    {
        return $id == Session::get(static::$session);
    }

    /**
     * Attempt to log in using credentials. Will update the session if
     * the login attempt has been successful
     *
     * @param string $username username as given by the user
     * @param string $password password as given by the user
     *
     * @return bool whether the login attempt was successful
     * @throws \Exception
     */
    public static function attempt($username, $password)
    {
        if ($user = User::where('username', '=', $username)->where('status', '=', 'active')->fetch()) {

            // found a valid user, now check the password
            if (Hash::check($password, $user->password)) {

                // store user ID in the session
                Session::put(static::$session, $user->id);

                return true;
            }
        }

        return false;
    }

    /**
     * Logs out the current user by deleting the auth key from the session
     *
     * @return void
     */
    public static function logout()
    {
        Session::erase(static::$session);
    }
}
