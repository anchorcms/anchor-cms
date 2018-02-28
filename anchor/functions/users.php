<?php

/************************************
 *  Theme functions for logged in user
 *************************************/

/**
 * Whether the user is authenticated
 *
 * @return bool
 */
function user_authed()
{
    return ! Auth::guest();
}

/**
 * Retrieves the ID of the authenticated user
 *
 * @return string|null
 * @throws \Exception
 */
function user_authed_id()
{
    if ($user = Auth::user()) {
        return $user->id;
    }
}

/**
 * Retrieves the username of the authenticated user
 *
 * @return string|null
 * @throws \Exception
 */
function user_authed_name()
{
    if ($user = Auth::user()) {
        return $user->username;
    }
}

/**
 * Retrieves the email address of the authenticated user
 *
 * @return string|null
 * @throws \Exception
 */
function user_authed_email()
{
    if ($user = Auth::user()) {
        return $user->email;
    }
}

/**
 * Retrieves the role of the authenticated user
 *
 * @return string|null
 * @throws \Exception
 */
function user_authed_role()
{
    if ($user = Auth::user()) {
        return $user->role;
    }
}

/**
 * Retrieves the real name of the authenticated user
 *
 * @return string|null
 * @throws \Exception
 */
function user_authed_real_name()
{
    if ($user = Auth::user()) {
        return $user->real_name;
    }
}

/**
 * Retrieves the user model object
 *
 * @return \stdClass
 * @throws \Exception
 */
function user_object()
{
    return Auth::user();
}

/**
 * Checks whether the current user is an admin
 *
 * @return bool
 * @throws \Exception
 */
function user_is_admin()
{
    return user_authed_role() == 'administrator';
}
