<?php namespace System\Database;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

use System\Database as DB;
use System\Config;

class Query {

	private $sql = '';
	private $bindings = array();
	private $wrapper = '`';
	private $table, $select, $join, $where, $group = array(), $order = array(), $limit, $offset;
	private $connection, $style;

	private function wrap($value) {
		// remove white space
		$column = trim($value);

		// handle aliases
		if(strpos(strtolower($column), ' as ') !== false) {
			list($col, $alias) = explode(' as ', strtolower($column));
			return $this->wrap($col) . ' AS ' . $this->wrap($alias);
		}

		// dont wrap function calls
		if(preg_match('/[a-z]+\(.*?\)/i', $column)) {
			return $column;
		}

		foreach(explode('.', $column) as $segment) {
			$sql[] = ($segment !== '*') ? $this->wrapper . $segment . $this->wrapper : $segment;
		}

		return implode('.', $sql);
	}

	private function columnizer($value) {
		if(is_array($value)) {
			foreach($value as $column) {
				$sql[] = $this->wrap($column);
			}

			return implode(', ', $sql);
		}

		return $this->wrap($value);
	}

	public static function table($table) {
		return new static($table);
	}

	public function __construct($table, $connection = null) {
		$this->table = $table;
		$this->connection = DB::connection($connection);
		$this->style = Config::get('database.fetch');
	}


	/*
		MySQL Joins

		Example:

		Db::table('users')
			->join('groups', 'groups.id', '=', 'users.group')
			->get(array('users.id', 'groups.name'));

	*/
	public function join($table, $left, $operator, $right, $type = 'INNER') {
		$this->join .= ' ' . $type . ' JOIN ' . $this->wrap($table) . ' ON (' . $this->wrap($left) . ' ' . $operator . ' ' . $this->wrap($right) . ')';

		return $this;
	}

	public function left_join($table, $left, $operator, $right) {
		return $this->join($table, $left, $operator, $right, 'LEFT');
	}

	/*
		MySQL where clauses
	*/
	public function where($column, $operator, $value) {
		$this->where .= (empty($this->where) ? ' WHERE ' : ' AND ') . $this->wrap($column) . ' ' . $operator . ' ?';
		$this->bindings[] = $value;

		return $this;
	}

	public function or_where($column, $operator, $value) {
		$this->where .= ' OR ' . $this->wrap($column) . ' ' . $operator . ' ?';
		$this->bindings[] = $value;

		return $this;
	}

	public function where_in($column, $keys) {
		if(count($keys)) {
			$instance = $this->connection->pdo;

			$keys = array_map(function($string) use ($instance) {
				return $instance->quote($string);
			}, $keys);

			$this->where .= (empty($this->where) ? ' WHERE ' : ' AND ') . $this->wrap($column) . ' IN (' . implode(',', $keys) . ')';
		}

		return $this;
	}

	public function where_is_null($column) {
		$this->where .= (empty($this->where) ? ' WHERE ' : ' AND ') . $this->wrap($column) . ' IS NULL';

		return $this;
	}

	public function or_where_is_null($column) {
		$this->where .= ' OR ' . $this->wrap($column) . ' IS NULL';

		return $this;
	}

	/*
		MySQL Sorting
	*/
	public function take($num) {
		$this->limit = ' LIMIT ' . $num;
		return $this;
	}

	public function skip($num) {
		$this->offset = ' OFFSET ' . $num;
		return $this;
	}

	public function order_by($column, $mode = 'ASC') {
		$this->order[] = $this->wrap($column) . ' ' . strtoupper($mode);
		return $this;
	}

	public function group_by($column) {
		$this->group[] = $this->wrap($column);

		return $this;
	}

	/*
		Build SQL statement
	*/
	public function build() {
		$select = empty($this->select) ? '*' : $this->columnizer($this->select);

		$ordering = count($this->order) ? ' ORDER BY ' . implode(', ', $this->order) : '';
		$grouping = count($this->group) ? ' GROUP BY ' . implode(', ', $this->group) : '';

		return 'SELECT ' . $select . ' FROM ' . $this->table . $this->join . $this->where . $grouping . $ordering . $this->limit . $this->offset;
	}

	/*
		MySQL select first col from first record

		Example:

		Query::table('visits')
			->where('id', '=', 26)
			->col();

	*/
	public function col($column_number = 0) {
		$sql = $this->build();

		list($statement, $result) = $this->connection->execute($sql, $this->bindings);

		if($result) return $statement->fetchColumn($column_number);
	}

	/*
		MySQL select first record

		Example:

		Query::table('books')
			->where('id', '=', 26)
			->fetch(array('name', 'author'));

	*/
	public function fetch($columns = array()) {
		$this->select = $columns;
		$sql = $this->build();

		list($statement, $result) = $this->connection->execute($sql, $this->bindings);

		if($result) return $statement->fetch($this->style);
	}

	/*
		MySQL get result set

		Example:

		Query::table('books')
			->get(array('name', 'author'));

	*/
	public function get($columns = array()) {
		$this->select = $columns;
		$sql = $this->build();

		list($statement, $result) = $this->connection->execute($sql, $this->bindings);

		if($result) return $statement->fetchAll($this->style);
	}

	/*
		Aggregate methods
	*/
	public function count() {
		$this->select = 'COUNT(*)';
		$sql = $this->build();

		list($statement, $result) = $this->connection->execute($sql, $this->bindings);

		if($result) return $statement->fetchColumn();
	}


	/*
		MySQL Insert

		Example:

		Query::table('my_table')
			->insert(array('id' => 1));

	*/
	public function insert($data) {
		foreach($data as $k => $v) {
			$keys[] = $this->wrap($k);
			$tokens[] = '?';
			$bindings[] = $v;
		}

		$sql = 'INSERT INTO ' . $this->wrap($this->table) . ' (' . implode(', ', $keys) . ') values (' . implode(', ', $tokens) . ')';

		list($statement, $result) = $this->connection->execute($sql, $bindings);

		if($result) return $statement;
	}

	/*
		MySQL Insert

		Example:

		Query::table('my_table')
			->insert(array('id' => 1));

	*/
	public function insert_get_id($data) {
		if($statement = $this->insert($data)) {
			return $this->connection->pdo->lastInsertId();
		}
	}

	/*
		MySQL Update

		Example:

		Query::table('my_table')
			->where('id', '=', 2)
			->update(array('name' => 'dave'));

	*/
	public function update($data) {
		foreach($data as $k => $v) {
			$update[] = $this->wrap($k) . ' = ?';
			$bindings[] = $v;
		}

		$sql = 'UPDATE ' . $this->wrap($this->table) . ' SET ' . implode(', ', $update) . $this->where;
		$bindings = array_merge($bindings, $this->bindings);

		list($statement, $result) = $this->connection->execute($sql, $bindings);

		if($result) return $statement->rowCount();
	}

	/*
		MySQL Delete

		Example:

		Query::table('my_table')
			->where('id', '=', 1)
			->delete();

	*/
	public function delete() {
		$sql = 'DELETE FROM ' . $this->wrap($this->table) . $this->where;

		list($statement, $result) = $this->connection->execute($sql, $this->bindings);

		if($result) return $statement->rowCount();
	}

}