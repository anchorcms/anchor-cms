<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for users
*/
function user_authed() {
	return Users::authed() !== false;
}
