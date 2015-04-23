<?php

/**
*	Theme functions for logged in user
*/

/**
 * Is there a user that's authenticated?
 * @return boolean
 */
function user_authed() {
	return ! Auth::guest();
}

/**
 * Grab the authenticated users' ID
 * @return int
 */
function user_authed_id() {
	if($user = Auth::user()) return $user->id;
}

/**
 * Grab the authenticated users' username
 * @return String
 */
function user_authed_name() {
	if($user = Auth::user()) return $user->username;
}

/**
 * Grab the authenticated users' email
 * @return String
 */
function user_authed_email() {
	if($user = Auth::user()) return $user->email;
}

/**
 * Grab the authenticated users' role
 * @return String
 */
function user_authed_role() {
	if($user = Auth::user()) return $user->role;
}

/**
 * Grab the authenticated users' real name
 * @return String
 */
function user_authed_real_name() {
	if($user = Auth::user()) return $user->real_name;
}

/**
 * Grab the authenticated user - as in the object itself
 */
function user_object() {
	return Auth::user();
}
