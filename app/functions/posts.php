<?php

function has_posts() {
	global $app;

	return $app['theme']->getVar('content')->count() > 0;
}

function posts() {
	global $app;

	return $app['theme']->getVar('content')->loop();
}

function posts_next($text = 'Next &rarr;', $default = '') {}

function posts_prev($text = '&larr; Previous', $default = '') {}

function total_posts() {
	global $app;

	return $app['theme']->getVar('content')->count();
}

function has_pagination() {}

function posts_per_page() {
	return site_meta('posts_per_page', 10);
}
