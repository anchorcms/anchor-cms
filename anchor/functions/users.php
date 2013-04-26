<?php

/**
 * Returns true if the current user is logged in.
 *
 * Sets the current user in the Registry
 *
 * @return bool
 */
function user_authed() {
	if($user = Registry::get('authuser')) {
		return true;
	}

	if($user = Auth::user()) {
		Registry::set('authuser', $user);

		return true;
	}

	return false;
}

/**
 * Returns the authed user ID
 *
 * @return string
 */
function user_authed_id() {
	if(user_authed()) {
		return Registry::prop('authuser', 'id');
	}
}

/**
 * Returns the authed user name
 *
 * @return string
 */
function user_authed_name() {
	if(user_authed()) {
		return Registry::prop('authuser', 'username');
	}
}


/**
 * Returns the authed user email
 *
 * @return string
 */
function user_authed_email() {
	if(user_authed()) {
		return Registry::prop('authuser', 'email');
	}
}

/**
 * Returns the authed user role (administrator, editor, user)
 *
 * @return string
 */
function user_authed_role() {
	if(user_authed()) {
		return Registry::prop('authuser', 'role');
	}
}

/**
 * Returns the authed user real name (display name)
 *
 * @return string
 */
function user_authed_real_name() {
	if(user_authed()) {
		return Registry::prop('authuser', 'real_name');
	}
}