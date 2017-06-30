<?php

class csrf
{

    public static function check($userToken)
    {
        if ($sessionToken = Session::get('csrf_token')) {
            return hash_equals($sessionToken, $userToken);
        }

        return false;
    }

    public static function token()
    {
        if ($sessionToken = Session::get('csrf_token')) {
            return $sessionToken;
        }

        $token = noise(64);

        Session::put('csrf_token', $token);

        return $token;
    }
}
