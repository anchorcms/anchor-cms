<?php

namespace System\request;

/**
 * Nano
 * Just another php framework
 *
 * @package    nano
 * @link       http://madebykieron.co.uk
 * @copyright  http://unlicense.org/
 */

/**
 * server class
 *
 * @package System\request
 */
class server
{
    /**
     * Array data from the SERVER global
     *
     * @var array
     */
    private $data;

    /**
     * Server object constructor
     *
     * @param array
     */
    public function __construct($array)
    {
        $this->data = $array;
    }

    /**
     * Get a server array item
     *
     * @param string     $key      key to retrieve from the server data
     * @param mixed|null $fallback fallback value for missing keys
     *
     * @return mixed|null key if found, fallback if given or null
     */
    public function get($key, $fallback = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $fallback;
    }

    /**
     * Set a server array item
     *
     * @param string $key   name of the key to set
     * @param string $value value to set for the key
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Remove a server array item
     *
     * @param string $key name of the key to erase
     *
     * @return void
     */
    public function erase($key)
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }
    }

    /**
     * Check if a server array item exists
     *
     * @param string $key name of the key to check
     *
     * @return bool whether the server data item exists
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }
}
