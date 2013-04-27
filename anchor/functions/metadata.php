<?php

/**
 * Theme functions for meta
 */
function site_name() {
	return Config::meta('sitename');
}

function site_description() {
	return Config::meta('description');
}

function site_meta($key, $default = '') {
	return Config::meta('custom_' . $key, $default);
}