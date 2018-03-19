<?php

namespace System;

/**
 * Nano
 * Just another php framework
 *
 * @package    nano
 * @link       http://madebykieron.co.uk
 * @copyright  http://unlicense.org/
 */

use ErrorException;
use OverflowException;
use System\Request\Server;

/**
 * uri class
 *
 * @package System
 */
class uri
{

    /**
     * The current request URI
     *
     * @var string
     */
    public static $current;

    /**
     * Get full secure URL relative to the application
     *
     * @param string $uri base request URI
     *
     * @return string full secure URL
     */
    public static function secure($uri)
    {
        return static::full($uri, true);
    }

    /**
     * Get full URL relative to the application and request scheme
     *
     * @param string    $uri    base request URI
     * @param bool|null $secure whether the URL should use https
     *
     * @return string full URL
     */
    public static function full($uri, $secure = null)
    {
        if (strpos($uri, '://')) {
            return $uri;
        }

        // create a server object from global
        $server = new Server($_SERVER);

        if ( ! is_null($secure)) {
            $scheme = $secure ? 'https://' : 'http://';
        } else {
            $scheme = ($server->has('HTTPS') and $server->get('HTTPS')) !== '' ? 'http://' : 'https://';
        }

        return $scheme . $server->get('HTTP_HOST') . static::to($uri);
    }

    /**
     * Get a path relative to the application
     *
     * @param string $uri destination request URI
     *
     * @return string resolved destination path
     */
    public static function to($uri)
    {
        if (strpos($uri, '://')) {
            return $uri;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $base = Config::app('url', '');

        /** @noinspection PhpUndefinedMethodInspection */
        if ($index = Config::app('index', '')) {
            $index .= '/';
        }

        return rtrim($base, '/') . '/' . $index . ltrim($uri, '/');
    }

    /**
     * Get the current URI string
     *
     * @return string current request URI
     * @throws \ErrorException
     * @throws \OverflowException
     */
    public static function current()
    {
        if (is_null(static::$current)) {
            static::$current = static::detect();
        }

        return static::$current;
    }

    /**
     * Try and detect the current URI
     *
     * @return string current request URI
     * @throws \ErrorException
     * @throws \OverflowException
     */
    public static function detect()
    {
        // create a server object from global
        $server = new Server($_SERVER);

        $try = ['REQUEST_URI', 'PATH_INFO', 'ORIG_PATH_INFO'];

        foreach ($try as $method) {

            // make sure the server var exists and is not empty
            if ($server->has($method) and $uri = $server->get($method)) {

                // apply a string filter and make sure we still have something left
                if ($uri = filter_var($uri, FILTER_SANITIZE_URL)) {

                    // make sure the uri is not malformed and return the pathname
                    if ($uri = parse_url($uri, PHP_URL_PATH)) {
                        return static::format($uri, $server);
                    }

                    // woah jackie, we found a bad'n
                    throw new ErrorException('Malformed URI');
                }
            }
        }

        throw new OverflowException('Uri was not detected. Make sure the REQUEST_URI is set.');
    }

    /**
     * Format the URI string remove any malicious
     * characters and relative paths
     *
     * @param string                 $uri    request URI to format
     * @param \System\request\server $server server object
     *
     * @return string formatted URI
     */
    public static function format($uri, $server)
    {
        // remove all characters except letters,
        // digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=.
        $uri = filter_var(rawurldecode($uri), FILTER_SANITIZE_URL);

        // remove script path/name
        $uri = static::remove_script_name($uri, $server);

        // remove the relative uri
        $uri = static::remove_relative_uri($uri);

        // return argument if not empty or return a single slash
        return trim($uri, '/') ?: '/';
    }

    /**
     * Remove the SCRIPT_NAME from the URI path
     *
     * @param string                 $uri    request URI to remove the script name from
     * @param \System\request\server $server server object
     *
     * @return string processed request URI string
     */
    public static function remove_script_name($uri, $server)
    {
        return static::remove($server->get('SCRIPT_NAME'), $uri);
    }

    /**
     * Remove a value from the start of a string
     * in this case the passed URI string
     *
     * @param string $value value to remove
     * @param string $uri   request URI to remove the value from
     *
     * @return string processed request URI string
     */
    public static function remove($value, $uri)
    {
        // make sure our search value is a non-empty string
        if (is_string($value) and strlen($value)) {

            // if the search value is at the start sub it out
            if (strpos($uri, $value) === 0) {
                $uri = substr($uri, strlen($value));
            }
        }

        return $uri;
    }

    /**
     * Remove the relative path from the URI set in the application config
     *
     * @param string $uri request URI to remove the Anchor path from
     *
     * @return string processed request URI
     */
    public static function remove_relative_uri($uri)
    {
        // remove base url
        /** @noinspection PhpUndefinedMethodInspection */
        if ($base = Config::app('url')) {
            $uri = static::remove(rtrim($base, '/'), $uri);
        }

        // remove index
        /** @noinspection PhpUndefinedMethodInspection */
        if ($index = Config::app('index')) {
            $uri = static::remove('/' . $index, $uri);
        }

        return $uri;
    }
}
