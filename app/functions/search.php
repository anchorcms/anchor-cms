<?php

namespace Anchorcms;

function has_search_results() {
	global $app;

	return $app['theme']->getVar('content')->count() > 0;
}

function total_search_results() {
	global $app;

	return $app['theme']->getVar('content')->count();
}

function search_results() {
	global $app;

	return $app['theme']->getVar('content')->loop();
}

function search_term() {
	global $app;

	return $app['theme']->getVar('keywords');
}

function has_search_pagination() {}
function search_next($text = 'Next', $default = '') {}
function search_prev($text = 'Previous', $default = '') {}

function search_url() {
	global $app;

	return $app['url']->to('/search');
}

function search_form_input($extra = '') {
	return sprintf('<input name="q" type="search" %s>', $extra);
}
