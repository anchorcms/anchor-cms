<?php

/*************************************
 *  Functions for theme configuration
 *************************************/

use System\config;

/**
 * Sets theme options
 *
 * @param string|array     $options theme option name or names
 * @param mixed|array|null $value   (optional) theme option value or values
 *
 * @return void
 */
function set_theme_options($options, $value = null)
{
    if ( ! is_array($options)) {
        $options = [$options => $value];
    }

    // existing options
    $current = Config::get('theme', []);

    // merge theme config
    Config::set('theme', array_merge($current, $options));
}

/**
 * Retrieves a theme option
 *
 * @param string $option  name of the option to retrieve
 * @param string $default (optional) fallback value for missing option
 *
 * @return mixed|string option value if found, default if provided, empty string otherwise
 */
function theme_option($option, $default = '')
{
    return Config::get('theme.' . $option, $default);
}
