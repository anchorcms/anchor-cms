<?php

use System\config;
use System\database\query;
use System\database\record;

/**
 * base class
 *
 * @property int $id
 */
class base extends Record
{

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws \ErrorException
     */
    public static function __callStatic($method, $arguments)
    {
        $obj = Query::table(static::table())->apply(get_called_class());

        if (method_exists($obj, $method)) {
            return call_user_func_array([$obj, $method], $arguments);
        }
    }

    /**
     * @param string|null $name
     *
     * @return string
     */
    public static function table($name = null)
    {
        if (is_null(static::$prefix)) {
            static::$prefix = Config::db('prefix', '');
        }

        if ( ! is_null($name)) {
            return static::$prefix . $name;
        }

        return static::$prefix . static::$table;
    }
}
