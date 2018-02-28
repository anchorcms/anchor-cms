<?php

/***************************
 * Theme functions for meta
 ***************************/

use System\config;

/**
 * Retrieves the site name
 *
 * @return string
 */
function site_name()
{
    return Config::meta('sitename');
}

/**
 * Retrieves the site description
 *
 * @return string
 */
function site_description()
{
    return Config::meta('description');
}

/**
 * Retrieves a site metadata key
 *
 * @param string       $key     name of the metadata key to retrieve
 * @param mixed|string $default default for missing keys
 *
 * @return mixed key value if found, default if given, empty string otherwise
 */
function site_meta($key, $default = '')
{
    return Config::meta('custom_' . $key, $default);
}
