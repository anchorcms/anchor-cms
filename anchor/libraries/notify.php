<?php

use System\session;

/**
 * notify class
 * Provides notifications that are flashed to the session
 * @method static error(string | string[] $messages): void
 * @method static success(string | string[] $messages): void
 * @method static notice(string | string[] $messages): void
 * @method static warning(string | string[] $messages): void
 */
class notify
{
    /**
     * Holds all available notification types
     * TODO: Should change to class constants
     *
     * @var array
     */
    public static $types = [
        'error',
        'notice',
        'success',
        'warning'
    ];

    /**
     * HTML string to contain the notification
     *
     * @var string
     */
    public static $wrap = '<div class="notifications">%s</div>';

    /**
     * HTML string to contain the message
     *
     * @var string
     */
    public static $mwrap = '<p class="%s">%s</p>';

    /**
     * Reads all notifications from the session
     *
     * @return string HTML notification output
     */
    public static function read()
    {
        $types = Session::get('messages');

        // no messages, no problem <-- Imagine this in a Borat accent
        if (is_null($types)) {
            return '';
        }

        $html = '';

        // iterate on all types and on all messages
        foreach ($types as $type => $messages) {
            foreach ($messages as $message) {
                $html .= sprintf(static::$mwrap, $type, implode('<br>', (array)$message));
            }
        }

        Session::erase('messages');

        return sprintf(static::$wrap, $html);
    }

    /**
     * Shorthand to add notifications using the method as the notification type
     *
     * @example Notify::error('something gone wrong');
     *
     * @param string $method     notification type
     * @param array  $parameters notification parameters
     *
     * @return void
     */
    public static function __callStatic($method, $parameters = [])
    {
        static::add($method, array_shift($parameters));
    }

    /**
     * Adds a new notification
     *
     * @param string          $type     notification type (one of static::$types)
     * @param string|string[] $messages notification message(s) to add
     *
     * @return void
     */
    public static function add($type, $messages)
    {
        if ( ! in_array($type, static::$types)) {
            return;
        }

        if ( ! is_array($messages)) {
            $messages = [$messages];
        }

        $key = sprintf('messages.%s', $type);

        $messages = array_merge(Session::get($key, []), $messages);

        Session::put($key, $messages);
    }
}
