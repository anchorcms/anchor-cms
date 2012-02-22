<?php defined('IN_CMS') or die('No direct access allowed.');

/*
  An extension of the Config class
  designed to focus specifically on configuring themes
*/
class ThemeConfig extends Config {

  /*
    has to be public so Config can read it
  */
  public static $items = array();

  /*
   * Allows a default config file
   * to be created within the theme directory
   *
   * @author Baylor Rae'
   * @package AnchorCMS
   * @since 0.5
   */
  public static function load() {
    $theme_config_file = Template::path() . 'config.php';
    if( file_exists($theme_config_file) ) {
      static::$items = require $theme_config_file;
    }
  }
   
}
