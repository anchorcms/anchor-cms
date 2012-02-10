<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for users
*/
function user_authed() {
	return Users::authed() !== false;
}

function user_authed_id() {
	if($user = Users::authed()) {
		return $user->id;
	}

	return '';
}

function user_authed_name() {
	if($user = Users::authed()) {
		return $user->username;
	}

	return '';
}

function user_authed_email() {
	if($user = Users::authed()) {
		return $user->email;
	}

	return '';
}

function user_authed_role() {
	if($user = Users::authed()) {
		return $user->role;
	}

	return '';
}

function user_authed_real_name() {
	if($user = Users::authed()) {
		return $user->real_name;
	}

	return '';
}