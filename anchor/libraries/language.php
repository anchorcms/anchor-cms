<?php

use System\config;

/**
 * language class
 * Reads translation files and parses translation strings
 */
class language
{

    /**
     * Holds all translatable lines
     *
     * @var array
     */
    private static $lines = [];

    /**
     * Translates a line of text
     *
     * @param string $key     line to translate
     * @param string $default (optional) fallback value
     * @param array  $args    (optional) variables to replace
     *
     * @return string translated line
     */
    public static function line($key, $default = '', $args = [])
    {
        $parts = explode('.', $key);

        if (count($parts) > 1) {
            $file = array_shift($parts);
            $line = array_shift($parts);
        }

        if (count($parts) == 1) {
            $file = 'global';
            $line = array_shift($parts);
        }

        if ( ! isset(static::$lines[$file])) {
            static::load($file);
        }

        if (isset(static::$lines[$file][$line])) {
            $text = static::$lines[$file][$line];
        } elseif ($default) {
            $text = $default;
        } else {
            $text = $key;
        }

        if (count($args)) {
            return call_user_func_array('sprintf', array_merge([$text], $args));
        }

        return $text;
    }

    /**
     * Loads a translation file
     *
     * @param string $file translation file filesystem name
     *
     * @return void
     */
    private static function load($file)
    {
        if (is_readable($lang = static::path($file))) {

            /** @noinspection PhpIncludeInspection */
            static::$lines[$file] = require $lang;
        }
    }

    /**
     * Resolves the path to a translation file
     *
     * @param string $file filename to resolve
     *
     * @return string resolved file path
     */
    private static function path($file)
    {
        $language = Config::app('language', 'en_GB');

        return APP . 'language/' . $language . '/' . $file . '.php';
    }
}
