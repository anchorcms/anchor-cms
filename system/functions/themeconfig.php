<?php defined('IN_CMS') or die('No direct access allowed.');

/*
  Functions for theme configuration
*/
function set_theme_options(array $options) {
  
  foreach( $options as $option => $value )
    ThemeConfig::set($option, $value);
}

function theme_option($option, $default_value = false) {
  return ThemeConfig::get($option, $default_value);
}
