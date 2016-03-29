<?php

function site_name() {
	global $app;

	return $app['mappers.meta']->key('sitename');
}

function site_description() {
	global $app;

	return $app['mappers.meta']->key('description');
}

function site_meta($key, $default = '') {
	global $app;

	return $app['mappers.meta']->key($key, $default);
}
