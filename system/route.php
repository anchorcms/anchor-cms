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

use Closure;
use Response;

/**
 * route class
 * @method static error(string | string[] $patterns, \Closure | \Closure[] $callbacks): void
 * @method static any(string | string[] $patterns, \Closure | \Closure[] $callbacks): void
 * @method static get(string | string[] $patterns, \Closure | \Closure[] $callbacks): void
 * @method static post(string | string[] $patterns, \Closure | \Closure[] $callbacks): void
 *
 * @package System
 */
class route
{
    /**
     * Array of collection actions
     *
     * @var array
     */
    public static $collection = [];

    /**
     * Array of callable functions
     *
     * @var array
     */
    public $callbacks;

    /**
     * The collected arguments from the uri match
     *
     * @var array
     */
    public $args = [];

    /**
     * Create a new instance of the Route class
     *
     * @param \Closure[] $callbacks route callbacks
     * @param array      $args      (optional) route arguments
     */
    public function __construct($callbacks, $args = [])
    {
        $this->callbacks = $callbacks;
        $this->args      = $args;
    }

    /**
     * Define a route using the method name as the request method to listen for
     *
     * @param string $method    HTTP request method this route should respond to
     * @param array  $arguments arguments to call
     */
    public static function __callStatic($method, $arguments)
    {
        static::register($method, array_shift($arguments), array_shift($arguments));
    }

    /**
     * Register a route on the router
     *
     * @param string              $method    HTTP request method this route should respond to
     * @param string|string[]     $patterns  URL patterns this route should respond to
     * @param \Closure|\Closure[] $arguments callback(s) this route should be handled with
     *
     * @return void
     */
    public static function register($method, $patterns, $arguments)
    {
        $method = strtoupper($method);

        if ($arguments instanceof Closure) {
            $arguments = ['main' => $arguments];
        }

        // add collection actions
        $arguments = array_merge($arguments, static::$collection);

        foreach ((array)$patterns as $pattern) {
            Router::$routes[$method][$pattern] = $arguments;
        }
    }

    /**
     * Register an action on the router
     *
     * @param string          $name     action name
     * @param string|\Closure $callback action handler
     *
     * @return void
     */
    public static function action($name, $callback)
    {
        Router::$actions[$name] = $callback;
    }

    /**
     * Start a collection of routes with common actions
     *
     * @param array|string    $actions     collection name
     * @param string|\Closure $definitions collection routes
     *
     * @return void
     */
    public static function collection($actions, $definitions)
    {
        // start collection
        static::$collection = $actions;

        // run definitions
        call_user_func($definitions);

        // end of collection
        static::$collection = [];
    }

    /**
     * Calls the route actions and returns a response object
     *
     * @return \System\response
     */
    public function run()
    {
        // call before actions
        $response = $this->before();

        // if we didn't get a response run the main callback
        if (is_null($response)) {
            $response = call_user_func_array($this->callbacks['main'], $this->args);
        }

        // call any after actions
        $this->after($response);

        // if the response was a view get the output and create response
        if ($response instanceof View) {
            return Response::create($response->render());
        }

        // if we have a response object return it
        if ($response instanceof Response) {
            return $response;
        }

        return Response::create((string)$response);
    }

    /**
     * Calls before actions
     *
     * @return \System\response|null
     */
    public function before()
    {
        if ( ! isset($this->callbacks['before'])) {
            return null;
        }

        foreach (explode(',', $this->callbacks['before']) as $action) {

            // return the first response object
            if ($response = call_user_func_array(Router::$actions[$action], $this->args)) {
                return $response;
            }
        }

        return null;
    }

    /**
     * Calls after actions
     *
     * @param string $response
     *
     * @return void
     */
    public function after($response)
    {
        if ( ! isset($this->callbacks['after'])) {
            return;
        }

        foreach (explode(',', $this->callbacks['after']) as $action) {
            call_user_func(Router::$actions[$action], $response);
        }
    }
}
