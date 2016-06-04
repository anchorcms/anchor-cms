<?php

function full_url($url = '') {
	global $app;

	return (string) $app['url']->to($url);
}

function base_url($url = '') {
	return full_url($url);
}

function current_url() {
	global $app;

	return (string) $app['http.request']->getUri();
}

function raw_current_url() {
	return current_url();
}

function rss_url() {
	return full_url('/feed/rss');
}

function asset_url($url = '') {
	return theme_url($url);
}
