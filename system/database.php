<?php

namespace System;

use ErrorException;

/**
 * Nano
 * Just another php framework
 *
 * @package    nano
 * @link       http://madebykieron.co.uk
 * @copyright  http://unlicense.org/
 */

/**
 * database class
 * @method static array ask(string $sql, array $binds = [])
 *
 * @package System
 */
class database
{

    /**
     * The default charset.
     */
    const DEFAULT_CHARSET = 'utf8mb4';

    /**
     * The current database driver
     *
     * @var array
     */
    public static $connections = [];

    /**
     * Get a database connection profile
     *
     * @param string|null $name connection name
     *
     * @return array profile data
     * @throws \ErrorException
     */
    public static function profile($name = null)
    {
        return static::connection($name)->profile();
    }

    /**
     * Get a database connection by name or return the default
     *
     * @param string|null $name connector name
     *
     * @return \System\Database\Connector
     * @throws \ErrorException
     */
    public static function connection($name = null)
    {
        // use the default connection if none is specified
        if (is_null($name)) {
            $name = Config::db('default');
        }

        // if we have already connected just return the instance
        if (isset(static::$connections[$name])) {
            return static::$connections[$name];
        }

        // connect and return
        return (static::$connections[$name] = static::factory(
            Config::db('connections.' . $name)
        ));
    }

    /**
     * Create a new database connector from app config
     *
     * @param array $config database configuration details
     *
     * @return \System\Database\Connector Database connector
     * @throws \ErrorException
     */
    public static function factory($config)
    {
        switch ($config['driver']) {
            case 'mysql':
                return new Database\Connectors\Mysql($config);
            case 'sqlite':
                return new Database\Connectors\Sqlite($config);
        }

        throw new ErrorException('Unknown database driver');
    }

    /**
     * Magic method for calling database driver methods on the default connection
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws \ErrorException
     */
    public static function __callStatic($method, $arguments)
    {
        return call_user_func_array([static::connection(), $method], $arguments);
    }
}
