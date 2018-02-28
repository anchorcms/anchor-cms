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

use System\Response\Status;

/**
 * response class
 *
 * @package System
 */
class response
{

    /**
     * The final output
     *
     * @var string
     */
    public $output;

    /**
     * The response status
     *
     * @var int
     */
    public $status = 200;

    /**
     * Array of headers to be sent
     *
     * @var array
     */
    public $headers = [];

    /**
     * Create a new instance of the Response class
     *
     * @param string $output  response output string
     * @param int    $status  (optional) HTTP status code
     * @param array  $headers (optional) response headers
     */
    public function __construct($output, $status = 200, $headers = [])
    {
        $this->status = $status;
        $this->output = $output;

        foreach ($headers as $name => $value) {
            $this->headers[strtolower($name)] = $value;
        }
    }

    /**
     * Creates a response with a header to redirect
     *
     * @param string $uri    URI to redirect to
     * @param int    $status (optional) status code to redirect with
     *
     * @return \System\response
     */
    public static function redirect($uri, $status = 302)
    {
        // Scrub all output buffer before we redirect.
        // @see http://www.mombu.com/php/php/t-output-buffering-and-zlib-compression-issue-3554315-last.html
        while (ob_get_level() > 1) {
            ob_end_clean();
        }

        return static::create('', $status, ['Location' => Uri::to($uri)]);
    }

    /**
     * Create a new instance of the Response class for chaining
     *
     * @param string $output  response output string
     * @param int    $status  (optional) HTTP status code
     * @param array  $headers (optional) response headers
     *
     * @return \System\response
     */
    public static function create($output, $status = 200, $headers = [])
    {
        return new static($output, $status, $headers);
    }

    /**
     * Creates a response with the output as error view from the app
     * along with the status code
     *
     * @param int   $status HTTP status code
     * @param array $vars   (optional) template variables to replace
     *
     * @return \System\response
     */
    public static function error($status, $vars = [])
    {
        return static::create(
            View::create('error/' . $status, $vars)->render(),
            $status
        );
    }

    /**
     * Creates a response with the output as JSON
     *
     * @param mixed $output response output string to convert to JSON
     * @param int   $status (optional) HTTP status code
     *
     * @return \System\response
     */
    public static function json($output, $status = 200)
    {
        return static::create(
            json_encode($output),
            $status,
            ['content-type' => 'application/json; charset=' . Config::app('encoding', 'UTF-8')]
        );
    }

    /**
     * Sends the final headers cookies and output
     *
     * @return void
     */
    public function send()
    {
        // don't send headers for CLI
        if ( ! Request::cli()) {

            // create a status header
            Status::create($this->status)->header();

            // always make sure we send the content type
            if ( ! array_key_exists('content-type', $this->headers)) {
                $this->headers['content-type'] = 'text/html; charset=' . Config::app('encoding', 'UTF-8');
            }

            // output headers
            foreach ($this->headers as $name => $value) {
                header($name . ': ' . $value);
            }

            // send any cookies we may have stored in the cookie class
            foreach (Cookie::$bag as $cookie) {
                call_user_func_array('setcookie', array_values($cookie));
            }
        }

        // output the final content
        if ($this->output) {
            echo $this->output;
        }
    }
}
