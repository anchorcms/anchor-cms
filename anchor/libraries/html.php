<?php

use System\config;
use System\uri;

/**
 * html class
 * Allows to write syntactically correct HTML
 */
class html
{

    /**
     * Holds the form encoding
     *
     * @var string
     */
    public static $encoding;

    /**
     * Encodes HTML entities
     *
     * @param string $value string to encode HTML entities in
     *
     * @return string
     */
    public static function entities($value)
    {
        return htmlentities($value, ENT_QUOTES, static::encoding(), false);
    }

    /**
     * Retrieves the form encoding
     *
     * @return string
     */
    public static function encoding()
    {
        if (is_null(static::$encoding)) {
            static::$encoding = Config::app('encoding');
        }

        return static::$encoding;
    }

    /**
     * Decodes HTML entities
     *
     * @param string $value string to decode HTML entities in
     *
     * @return string
     */
    public static function decode($value)
    {
        return html_entity_decode($value, ENT_QUOTES, static::encoding());
    }

    /**
     * Escapes special characters
     *
     * @param string $value string to escape
     *
     * @return string
     */
    public static function specialchars($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, static::encoding(), false);
    }

    /**
     * Creates a link
     *
     * @param string $uri        URI the link should point to
     * @param string $title      link text
     * @param array  $attributes link attributes
     *
     * @return string
     */
    public static function link($uri, $title = '', $attributes = [])
    {
        if (strpos('#', $uri) !== 0) {
            $uri = Uri::to($uri);
        }

        if ($title == '') {
            $title = $uri;
        }

        $attributes['href'] = $uri;

        return static::element('a', $title, $attributes);
    }

    /**
     * Creates a form element
     *
     * @param string $name       element name
     * @param string $content    element value or content
     * @param null   $attributes element attributes
     *
     * @return string
     */
    public static function element($name, $content = '', $attributes = null)
    {
        $short = [
            'img',
            'input',
            'br',
            'hr',
            'frame',
            'area',
            'base',
            'basefont',
            'col',
            'isindex',
            'link',
            'meta',
            'param'
        ];

        if (in_array($name, $short)) {
            if ($content) {
                $attributes['value'] = $content;
            }

            return '<' . $name . static::attributes($attributes) . '>';
        }

        return '<' . $name . static::attributes($attributes) . '>' . $content . '</' . $name . '>';
    }

    /**
     * Creates an HTML attribute string
     *
     * @param string|array $attributes list of attributes to include
     *
     * @return string
     */
    public static function attributes($attributes)
    {
        if (empty($attributes)) {
            return '';
        }

        if (is_string($attributes)) {
            return ' ' . $attributes;
        }

        foreach ($attributes as $key => $val) {
            $pairs[] = $key . '="' . $val . '"';
        }

        /** @noinspection PhpUndefinedVariableInspection */
        return ' ' . implode(' ', $pairs);
    }
}
