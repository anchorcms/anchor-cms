<?php defined('IN_CMS') or die('No direct access allowed.');

class Db {

	private static $driver = 'mysql';
	private static $hostname, $username, $password, $database;
	private static $driver_options = array();

	private static $dbh = null;
	private static $affected_rows = 0;

	public static function connect() {
		// config
		$params = Config::get('database');
		
		// build dns string
		$dsn = 'mysql:dbname=' . $params['name'] . ';host=' . $params['host'];

		// try connection
		static::$dbh = new PDO($dsn, $params['username'], $params['password']);
		
		// set error handling to exceptions
		static::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return true;
	}

	public static function close() {
		static::$dbh = null;
	}

	/*
	 * Querying
	 */
	public static function query($sql, $binds = array()) {
		// make sure we have a connection
		if(is_null(static::$dbh)) {
			static::connect();
		}
	
		// check binds
		if(!is_array($binds)) {
			$binds = array($binds);
		}

		// prepare
		$sth = static::$dbh->prepare($sql);

		// get results
		$sth->execute($binds);

		// update affected rows
		static::$affected_rows = $sth->rowCount();

		// return statement
		return $sth;
	}

	/*
	 * Simple query, returns TRUE or FALSE
	 */
	public static function exec($sql, $binds = array()) {
		// make sure we have a connection
		if(is_null(static::$dbh)) {
			static::connect();
		}

		// check binds
		if(!is_array($binds)) {
			$binds = array($binds);
		}

		// prepare
		$sth = static::$dbh->prepare($sql);

		// get results
		$result = $sth->execute($binds);

		// update affected rows
		static::$affected_rows = $sth->rowCount();

		// return result
		return $result;
	}
	
	/*
	 * Shortcuts
	 */
	public static function update($table, $columns = array(), $condition = array()) {
		$updates = array();
		$args = array();

		foreach($columns as $key => $value) {
			$updates[] = '`' . $key . '` = ?';
			$args[] = $value;
		}
		
		$sql = "update `" . $table . "` set " . implode(', ', $updates);
		
		if(count($condition)) {
			$where = array();

			foreach($condition as $key => $value) {
				$where[] = '`' . $key . '` = ?';
				$args[] = $value;
			}

			$sql .= " where " . implode(' and ', $where);
		}	
		
		return Db::exec($sql, $args);
	}

	public static function insert($table, $row = array()) {
		$keys = array();
		$values = array();
		$args = array();
		
		foreach($row as $key => $value) {
			$keys[] = '`' . $key . '`';
			$values[] = '?';
			$args[] = $value;
		}
		
		$sql = "insert into `" . $table . "` (" . implode(', ', $keys) . ") values (" . implode(', ', $values) . ")";	

		return Db::exec($sql, $args);
	}

	public static function delete($table, $condition = array()) {
		$sql = "delete from `" . $table . "`";
		
		if(count($condition)) {
			$where = array();

			foreach($condition as $key => $value) {
				$where[] = '`' . $key . '` = ?';
				$args[] = $value;
			}

			$sql .= " where " . implode(' and ', $where);
		}	
		
		return Db::exec($sql, $args);
	}

	public static function row($sql, $binds = array(), $fetch_style = \PDO::FETCH_OBJ) {
		// get statement
		$stm = static::query($sql, $binds);

		// return data
		return $stm->fetch($fetch_style);
	}

	public static function results($sql, $binds = array(), $fetch_style = \PDO::FETCH_OBJ) {
		// get statement
		$stm = static::query($sql, $binds);

		// return data array
		return $stm->fetchAll($fetch_style);
	}

	public static function insert_id() {
		return static::$dbh->lastInsertId();
	}

	public static function affected_rows() {
		return static::$affected_rows;
	}

}
