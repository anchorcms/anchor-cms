<?php

use System\session;

/**
 * csrf class
 * Creates and verifies CSRF tokens
 */
class csrf
{
    /**
     * CSRF token key name for the user session
     */
    const SESSION_KEY  = 'csrf_token';

    /**
     * CSRF token length
     */
    const TOKEN_LENGTH = 64;

    /**
     * Checks the CSRF token
     *
     * @param string $userToken token to check
     *
     * @return bool
     */
    public static function check($userToken)
    {
        if ($sessionToken = Session::get(self::SESSION_KEY)) {
            return hash_equals($sessionToken, $userToken);
        }

        return false;
    }

    /**
     * Generates a CSRF token
     *
     * @return string
     */
    public static function token()
    {
        if ($sessionToken = Session::get(self::SESSION_KEY)) {
            return $sessionToken;
        }

        $token = noise(self::TOKEN_LENGTH);

        Session::put(self::SESSION_KEY, $token);

        return $token;
    }
}
