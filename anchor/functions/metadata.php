<?php

/**
 * Theme functions for meta
 */
 
/**
 * Grab the site name
 * @return String
 */
function site_name() {
	return Config::meta('sitename');
}

/**
 * Grab the site description
 * @return String
 */
function site_description() {
	return Config::meta('description');
}

/**
 * Grab the site meta data
 * @return String
 */
function site_meta($key, $default = '') {
	return Config::meta('custom_' . $key, $default);
}