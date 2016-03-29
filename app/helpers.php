<?php

function dd() {
	if( ! headers_sent()) {
		header('content-type: text/plain');
	}
	call_user_func_array('var_dump', func_get_args());
	exit(1);
}

function e($str) {
	return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
}

function url($url) {
	global $app;

	return $app['url']->to($url);
}

function asset($url) {
	global $app;

	return $app['url']->to($url);
}

function admin_url($url) {
	global $app;

	return $app['url']->to($url, 'admin');
}
