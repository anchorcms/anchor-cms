<?php

/**
 * Sets a theme value in the config class
 *
 * @param string
 * @param string
 */
function set_theme_options($options, $value = null) {
	if( ! is_array($options)) {
		$options = array($options => $value);
	}

	// existsing options
	$current = Config::get('theme', array());

	// merge theme config
	Config::set('theme', array_merge($current, $options));
}

/**
 * Retrieves a theme value in the config class
 *
 * @param string
 * @param string
 * @return string
 */
function theme_option($option, $default = '') {
	return Config::get('theme.' . $option, $default);
}