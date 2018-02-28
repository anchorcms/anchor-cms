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

use PDO;
use System\Database as DB;

/**
 * query class
 *
 * @package System\database
 */
class query extends Builder
{
    /**
     * The current database table
     *
     * @var string
     */
    public $table;

    /**
     * Database connector object
     *
     * @var object
     */
    public $connection;

    /**
     * The class name of the object to create when fetching from the database
     * TODO: Change to stdClass::class
     *
     * @var string
     */
    public $fetch_class = 'StdClass';

    /**
     * Array of columns to build the select
     *
     * @var array
     */
    public $columns = [];

    /**
     * Array of table joins to build
     *
     * @var array
     */
    public $join = [];

    /**
     * Array of where clauses to build
     *
     * @var array
     */
    public $where = [];

    /**
     * Columns to sort by
     *
     * @var array
     */
    public $sortby = [];

    /**
     * Columns to group by
     *
     * @var array
     */
    public $groupby = [];

    /**
     * Number of rows to limit
     *
     * @var int
     */
    public $limit;

    /**
     * Number of rows to offset
     *
     * @var int
     */
    public $offset;

    /**
     * Array values to bind to the query
     *
     * @var array
     */
    public $bind = [];

    /**
     * Create a new database query instance
     *
     * @param string $table      table to create a query for
     * @param null   $connection connection to create a query for
     *
     * @throws \ErrorException
     */
    public function __construct($table, $connection = null)
    {
        if (is_null($connection)) {
            $connection = DB::connection();
        }

        $this->table      = $table;
        $this->connection = $connection;
    }

    /**
     * Create a new database query instance for chaining
     *
     * @param string $table      table to create a query for
     * @param null   $connection connection to create a query for
     *
     * @return \System\database\query Query
     * @throws \ErrorException
     */
    public static function table($table, $connection = null)
    {
        if (is_null($connection)) {
            $connection = DB::connection();
        }

        return new static($table, $connection);
    }

    /**
     * Set the class name for fetch queries, return self for chaining
     *
     * @param string $class new return class name
     *
     * @return \System\database\query
     */
    public function apply($class)
    {
        $this->fetch_class = $class;

        return $this;
    }

    /**
     * Run a count function on database query
     *
     * @return string
     * @throws \Exception
     */
    public function count()
    {
        /** @var \PDOStatement $statement */
        list($result, $statement) = $this->connection->ask(
            $this->build_select_count(),
            $this->bind
        );

        return $statement->fetchColumn();
    }

    /**
     * Fetch a single column from the query
     *
     * @param string[] $columns       (optional) column names to fetch
     * @param int      $column_number (optional) number of column
     *
     * @return string result
     * @throws \Exception
     */
    public function column($columns = [], $column_number = 0)
    {
        /** @var \PDOStatement $statement */
        list($result, $statement) = $this->connection->ask(
            $this->build_select($columns),
            $this->bind
        );

        return $statement->fetchColumn($column_number);
    }

    /**
     * Fetch a single row from the query
     *
     * @param string[]|null $columns (optional) column names to fetch
     *
     * @return \stdClass results
     * @throws \Exception
     */
    public function fetch($columns = null)
    {
        /** @var \PDOStatement $statement */
        list($result, $statement) = $this->connection->ask(
            $this->build_select($columns),
            $this->bind
        );

        $statement->setFetchMode(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            $this->fetch_class
        );

        return $statement->fetch();
    }

    /**
     * Fetch a result set from the query
     *
     * @param string[]|null $columns (optional) column names to fetch
     *
     * @return array results
     * @throws \Exception
     */
    public function get($columns = null)
    {
        /** @var \PDOStatement $statement */
        list($result, $statement) = $this->connection->ask(
            $this->build_select($columns), $this->bind);

        $statement->setFetchMode(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            $this->fetch_class
        );

        return $statement->fetchAll();
    }

    /**
     * Insert a row into the database
     *
     * @param array $row row data to insert
     *
     * @return int number of affected rows
     * @throws \Exception
     */
    public function insert($row)
    {
        /** @var \PDOStatement $statement */
        list($result, $statement) = $this->connection->ask(
            $this->build_insert($row),
            $this->bind
        );

        return $statement->rowCount();
    }

    /**
     * Insert a row into the database and return the inserted ID
     *
     * @param array $row row data to insert
     *
     * @return int last inserted ID
     * @throws \Exception
     */
    public function insert_get_id($row)
    {
        $this->connection->ask(
            $this->build_insert($row),
            $this->bind
        );

        return $this->connection->instance()->lastInsertId();
    }

    /**
     * Update row in the database
     *
     * @param array $row row data to update
     *
     * @return int number of affected rows
     * @throws \Exception
     */
    public function update($row)
    {
        /** @var \PDOStatement $statement */
        list($result, $statement) = $this->connection->ask(
            $this->build_update($row),
            $this->bind
        );

        return $statement->rowCount();
    }

    /**
     * Delete a row in the database
     *
     * @return int number of affected rows
     * @throws \Exception
     */
    public function delete()
    {
        /** @var \PDOStatement $statement */
        list($result, $statement) = $this->connection->ask(
            $this->build_delete(),
            $this->bind
        );

        return $statement->rowCount();
    }

    /**
     * Add a where clause to the query
     *
     * @param string $column   name of the column
     * @param string $operator comparison operator
     * @param string $value    value to compare to
     *
     * @return \System\database\query
     */
    public function where($column, $operator, $value)
    {
        $this->where[] = (count($this->where) ? 'AND ' : 'WHERE ') . $this->wrap($column) . ' ' . $operator . ' ?';
        $this->bind[]  = $value;

        return $this;
    }

    /**
     * Add a where clause to the query starting with OR
     *
     * @param string $column   name of the column
     * @param string $operator comparison operator
     * @param string $value    value to compare to
     *
     * @return \System\database\query
     */
    public function or_where($column, $operator, $value)
    {
        $this->where[] = (count($this->where) ? 'OR ' : 'WHERE ') . $this->wrap($column) . ' ' . $operator . ' ?';
        $this->bind[]  = $value;

        return $this;
    }

    /**
     * Add a where clause to the query starting with IN
     *
     * @param string $column name of the column
     * @param array  $values values to check for
     *
     * @return \System\database\query
     */
    public function where_in($column, $values)
    {
        $this->where[] = (count($this->where) ? 'OR ' : 'WHERE ') .
                         $this->wrap($column) . ' IN (' . $this->placeholders(count($values)) . ')';

        $this->bind = array_merge($this->bind, $values);

        return $this;
    }

    /**
     * Add a left table join to the query
     *
     * @param string|\Closure $table    table to join on
     * @param string          $left     left field
     * @param string          $operator join operator
     * @param string          $right    right field
     *
     * @return \System\database\query
     */
    public function left_join($table, $left, $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, 'LEFT');
    }

    /**
     * Add a table join to the query
     *
     * @param string|\Closure $table    table to join on
     * @param string          $left     left field
     * @param string          $operator join operator
     * @param string          $right    right field
     * @param string          $type     (optional) join type. defaults to inner join
     *
     * @return \System\database\query
     */
    public function join($table, $left, $operator, $right, $type = 'INNER')
    {
        if ($table instanceof \Closure) {

            /** @var \System\database\query $query */
            list($query, $alias) = $table();

            $this->bind = array_merge($this->bind, $query->bind);

            $table = '(' . $query->build_select() . ') AS ' . $this->wrap($alias);
        } else {
            $table = $this->wrap($table);
        }

        $this->join[] = $type . ' JOIN ' . $table . ' ON (' . $this->wrap($left) . ' ' . $operator . ' ' . $this->wrap($right) . ')';

        return $this;
    }

    /**
     * Add a sort by column to the query
     *
     * @param string $column name of the column to sort by
     * @param string $mode   (optional) sorting mode (ASC or DESC)
     *
     * @return \System\database\query
     */
    public function sort($column, $mode = 'ASC')
    {
        $this->sortby[] = $this->wrap($column) . ' ' . strtoupper($mode);

        return $this;
    }

    /**
     * Add a group by column to the query
     *
     * @param string $column name of the column to group by
     *
     * @return \System\database\query
     */
    public function group($column)
    {
        $this->groupby[] = $this->wrap($column);

        return $this;
    }

    /**
     * Set a row limit on the query
     *
     * @param int $num maximum number of rows to retrieve
     *
     * @return \System\database\query
     */
    public function take($num)
    {
        $this->limit = $num;

        return $this;
    }

    /**
     * Set a row offset on the query
     *
     * @param int $num number of rows to skip
     *
     * @return \System\database\query
     */
    public function skip($num)
    {
        $this->offset = $num;

        return $this;
    }
}
