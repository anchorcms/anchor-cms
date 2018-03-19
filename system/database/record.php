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

/**
 * record class
 * @method static query get(string[] | null $columns = null)
 * @method static query where(string $column, string $operator, string $value)
 * @method static query left_join(string | \Closure $table, string $left, string $operator, string $right)
 * @method static query insert_get_id(array $row)
 * @method static string|int count()
 * @method static query sort(string $column, string $mode = 'ASC')
 * @method static int insert(array $row)
 *
 * @package System\database
 */
abstract class record
{

    /**
     * The database table name prefix
     *
     * @var string
     */
    public static $prefix;

    /**
     * The database table
     *
     * @var string
     */
    public static $table;

    /**
     * The database table primary key
     *
     * @var string
     */
    public static $primary = 'id';

    /**
     * Save found objects for faster lookups
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Holds the current record data
     *
     * @var array
     */
    public $data = [];

    /**
     * Create a new instance of the record class
     *
     * @param array
     */
    public function __construct($row = [])
    {
        $this->data = $row;
    }

    /**
     * Create a new instance of the record class for chaining
     *
     * @param array
     *
     * @return \stdClass
     * @throws \Exception
     */
    public static function create($row)
    {
        return static::find(static::insert_get_id($row));
    }

    /**
     * Find a record by primary key and return a new Record object
     *
     * @param int
     *
     * @return \stdClass|\System\database\record Record
     * @throws \Exception
     */
    public static function find($id)
    {
        $class = get_called_class();
        $key   = $class . $id;

        if (isset(static::$cache[$key])) {
            return static::$cache[$key];
        }

        return (static::$cache[$key] = static::where(static::$primary, '=', $id)->apply($class)->fetch());
    }

    /**
     * Commit data array to database matching the primary key
     *
     * @param int   $id  ID of the record to update
     * @param array $row row data to update
     *
     * @return int Affected Row
     * @throws \Exception
     */
    public static function update($id, $row)
    {
        return static::where(static::$primary, '=', $id)->update($row);
    }

    /**
     * Magic method for calling other Query methods
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws \ErrorException
     */
    public static function __callStatic($method, $arguments)
    {
        $obj = query::table(static::$prefix . static::$table)->apply(get_called_class());

        if (method_exists($obj, $method)) {
            return call_user_func_array([$obj, $method], $arguments);
        }
    }

    /**
     * Commit the data array to the database
     *
     * @return int affected rows
     * @throws \Exception
     */
    public function save()
    {
        if (isset($this->data[static::$primary])) {
            return static::where(static::$primary, '=', $this->data[static::$primary])->update($this->data);
        }

        return static::insert($this->data);
    }

    /**
     * Delete the record from the database
     *
     * @return int affected rows
     * @throws \Exception
     */
    public function delete()
    {
        return static::where(static::$primary, '=', $this->data[static::$primary])->delete();
    }

    /**
     * Set the data array
     *
     * @param array|\stdClass
     */
    public function populate($row)
    {
        $this->data = array_merge($this->data, (is_object($row) ? get_object_vars($row) : $row));
    }

    /**
     * Magic method for getting a item from the data array
     *
     * @param string $key name of the field to get
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
    }

    /**
     * Magic method for setting a item in the data array
     *
     * @param string $key   field name to set in the record
     * @param mixed  $value value to set in the record
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }
}
