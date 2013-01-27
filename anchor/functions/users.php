<?php

/**
	Theme functions for logged in user
*/
function user_authed() {
	return ! Auth::guest();
}

function user_authed_id() {
	if($user = Auth::user()) return $user->id;
}

function user_authed_name() {
	if($user = Auth::user()) return $user->username;
}

function user_authed_email() {
	if($user = Auth::user()) return $user->email;
}

function user_authed_role() {
	if($user = Auth::user()) return $user->role;
}

function user_authed_real_name() {
	if($user = Auth::user()) return $user->real_name;
}