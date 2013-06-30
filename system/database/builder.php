<?php namespace System\Database;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

abstract class Builder {

	/**
	 * Wrap array of column names
	 *
	 * @param array
	 * @return string
	 */
	public function wrap_columns($columns) {
		$wrapped = array();

		foreach($columns as $column) {
			$wrapped[] = $this->wrap_column($column);
		}

		return implode(', ', $wrapped);
	}

	/**
	 * Wrap a column
	 *
	 * @param string
	 * @return string
	 */
	public function wrap_column($value) {
		$params = array();

		// dont wrap function calls
		if(strpos($value, '(')) return $value;

		// separate alias if found
		if(strpos(strtolower($value), ' as ')) {
			$parts = explode(' ', $value);

			return $this->wrap_column($parts[0]) . ' AS ' . $this->wrap_column($parts[2]);
		}

		// enclose items
		$parts = explode('.', $value);

		foreach($parts as $index => $string) {
			if($string != '*') {
				if($index == 0 and count($parts) > 1) {
					$string = $this->wrap_table($string);
				}
				else {
					$string = $this->wrap_value($string);
				}
			}

			$params[] = $string;
		}

		return implode('.', $params);
	}

	/**
	 * Wrap table and include table prefix
	 *
	 * @param string
	 * @return string
	 */
	public function wrap_table($value) {
		if($this->connection->table_prefix) {
			if(strpos($value, $this->connection->table_prefix) === 0) {
				return $this->wrap_value($value);
			}
		}

		return $this->wrap_value($this->connection->table_prefix . $value);
	}

	/**
	 * Wrap value with database connector escape characters
	 *
	 * @param string
	 * @return string
	 */
	public function wrap_value($value) {
		return sprintf($this->connection->wrapper, $value);
	}

	/**
	 * Build placeholders to replace with values in a query
	 *
	 * @param int
	 * @return string
	 */
	public function placeholders($length, $holder = '?') {
		$holders = array();

		for($i = 0; $i < $length; $i++) {
			$holders[] = $holder;
		}

		return implode(', ', $holders);
	}

	/**
	 * Build the complete sql query
	 *
	 * @return string
	 */
	public function build() {
		$sql = '';

		if(count($this->join)) {
			$sql .= ' ' . implode(' ', $this->join);
		}

		if(count($this->where)) {
			$sql .= ' ' . implode(' ', $this->where);
		}

		if(count($this->groupby)) {
			$sql .= ' GROUP BY ' . implode(', ', $this->groupby);
		}

		if(count($this->sortby)) {
			$sql .= ' ORDER BY ' . implode(', ', $this->sortby);
		}

		if($this->limit) {
			$sql .= ' LIMIT ' . $this->limit;

			if($this->offset) {
				$sql .= ' OFFSET ' . $this->offset;
			}
		}

		return $sql;
	}

	/**
	 * Build table insert
	 *
	 * @param array
	 * @return string
	 */
	public function build_insert($row) {
		$keys = array_keys($row);
		$values = $this->placeholders(count($row));
		$this->bind = array_values($row);

		return 'INSERT INTO ' . $this->wrap_table($this->table) . ' (' . $this->wrap_columns($keys) . ') VALUES(' . $values . ')';
	}

	/**
	 * Build table update
	 *
	 * @param array
	 * @return string
	 */
	public function build_update($row) {
		$placeholders = array();
		$values = array();

		foreach($row as $key => $value) {
			$placeholders[] = $this->wrap_column($key) . ' = ?';
			$values[] = $value;
		}

		$update = implode(', ', $placeholders);
		$this->bind = array_merge($values, $this->bind);

		return 'UPDATE ' . $this->wrap_table($this->table) . ' SET ' . $update . $this->build();
	}

	/**
	 * Build the select columns of the query
	 *
	 * @param array
	 * @return string
	 */
	public function build_select($columns = null) {
		if(is_array($columns) and count($columns)) {
			$columns = $this->wrap_columns($columns);
		}
		else $columns = '*';

		return 'SELECT ' . $columns . ' FROM ' . $this->wrap_table($this->table) . $this->build();
	}

	/**
	 * Build a delete query
	 *
	 * @param array
	 * @return string
	 */
	public function build_delete() {
		return 'DELETE FROM ' . $this->wrap_table($this->table) . $this->build();
	}

	/**
	 * Build a select count query
	 *
	 * @return string
	 */
	public function build_select_count() {
		return 'SELECT COUNT(*) FROM ' . $this->wrap_table($this->table) . $this->build();
	}

}