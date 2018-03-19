<?php

namespace System\database;

/**
 * Nano
 * Just another php framework
 *
 * @package    nano
 * @link       http://madebykieron.co.uk
 * @copyright  http://unlicense.org/
 */

use Exception;
use System\Config;

/**
 * connector class
 *
 * @package System\database
 */
abstract class connector
{
    /**
     * Log of all queries
     *
     * @var array
     */
    private $queries = [];

    /**
     * Magic method for calling methods on PDO instance
     *
     * @param string
     * @param array
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return call_user_func_array([self::instance(), $method], $arguments);
    }

    /**
     * All connectors will implement a function to return the pdo instance
     * TODO: Should be static
     *
     * @return \PDO PDO Object
     */
    abstract public function instance();

    /**
     * A simple database query wrapper
     *
     * @param string $sql   SQL statement to execute
     * @param array  $binds variable binds to replace
     *
     * @return array result array
     * @throws \Exception
     */
    public function ask($sql, $binds = [])
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            if (Config::db('profiling')) {
                $this->queries[] = compact('sql', 'binds');
            }

            $statement = $this->instance()->prepare($sql);
            $result    = $statement->execute($binds);

            return [$result, $statement];
        } catch (Exception $e) {
            $error = 'Database Error: ' . $e->getMessage() . '</code></p><p><code>SQL: ' . trim($sql);
            throw new Exception($error, 0, $e);
        }
    }

    /**
     * Return the profile array
     *
     * @return array
     */
    public function profile()
    {
        return $this->queries;
    }

    /**
     * showQuery method from issue #695 by apmuthu
     * Show a formatted query given some parameters
     *
     * @param string $query  SQL query to show
     * @param array  $params parameters to replace
     *
     * @return null|string|string[]
     */
    public function showQuery($query, $params)
    {
        $keys   = [];
        $values = [];

        // build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_numeric($value)) {
                $values[] = intval($value);
            } else {
                $values[] = '"' . $value . '"';
            }
        }

        return preg_replace($keys, $values, $query, 1, $count);
    }
}
