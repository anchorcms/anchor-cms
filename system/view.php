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

/**
 * view class
 *
 * @package System
 */
class view
{

    /**
     * The current path to the view template file
     *
     * @var string
     */
    public $path;

    /**
     * Array of view variables
     *
     * @var array
     */
    public $vars = [];

    /**
     * Create a instance or the View class
     *
     * @param string $path relative path to the view template file
     * @param array  $vars (optional) view variables to replace
     */
    public function __construct($path, $vars = [])
    {
        $this->path = APP . 'views/' . $path . EXT;
        $this->vars = array_merge($this->vars, $vars);
    }

    /**
     * Create a instance of a View class for chaining using a method for the file name
     *
     * @example View::home(array('title' => 'Home'));
     *
     * @param string $method    method name used as view name
     * @param array  $arguments method arguments used as view variables
     *
     * @return \System\view view object
     */
    public static function __callStatic($method, $arguments)
    {
        $vars = count($arguments) ? current($arguments) : [];

        return new static($method, $vars);
    }

    /**
     * Render a partial view
     *
     * @param string $name partial name to replace in the view template
     * @param string $path relative path to the partial template file
     * @param array  $vars (optional) view variables to replace in the partial
     *
     * @return \System\view self for chaining
     */
    public function partial($name, $path, $vars = [])
    {
        $this->vars[$name] = static::create($path, $vars)->render();

        return $this;
    }

    /**
     * Render the view
     *
     * @return string parsed template string
     */
    public function render()
    {
        ob_start();

        extract($this->vars);

        /** @noinspection PhpIncludeInspection */
        require $this->path;

        return ob_get_clean();
    }

    /**
     * Create a instance or the View class for chaining
     *
     * @param string $path relative path to the view template file
     * @param array  $vars (optional) view variables to replace
     *
     * @return \System\view view object for chaining
     */
    public static function create($path, $vars = [])
    {
        return new static($path, $vars);
    }
}
