<?php

/**
 * events class
 * Basic event listener and responder
 */
class events
{
    /**
     * Holds the event stack
     *
     * @var array
     */
    private static $stack = [];

    /**
     * Binds a callback to the page
     *
     * @param string   $page page string to bind the event to
     * @param \Closure $fn   event handler callback
     *
     * @return void
     */
    public static function bind($page, $fn)
    {
        list($page, $name) = static::parse($page);

        if ( ! isset(static::$stack[$page])) {
            static::$stack[$page] = [];
        }

        static::$stack[$page][$name] = $fn;
    }

    /**
     * Parses a page
     *
     * @param string $page page string to parse
     *
     * @return array
     */
    private static function parse($page)
    {
        $name = 'main';

        if (strpos($page, '.') !== false) {
            list($page, $name) = explode('.', $page);
        }

        return [$page, $name];
    }

    /**
     * Calls an event handler
     *
     * @param string $name (optional) event to call
     *
     * @return string
     */
    public static function call($name = '')
    {
        $page = Registry::get('page');

        if (empty($name)) {
            $name = 'main';
        }

        if ($func = isset(static::$stack[$page->slug][$name]) ? static::$stack[$page->slug][$name] : false) {
            return is_callable($func) ? $func() : '';
        }

        return '';
    }
}
