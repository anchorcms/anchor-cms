<?php namespace System\Database;

use System\Config;
use PDO, PDOStatement, PDOException, Exception;

class Connection {

	public $pdo, $config, $queries = array();

	public function __construct(PDO $pdo, $config) {
		$this->pdo = $pdo;
		$this->config = $config;
	}

	public function transaction($callback) {
		$this->pdo->beginTransaction();

		// After beginning the database transaction, we will call the callback
		// so that it can do its database work. If an exception occurs we'll
		// rollback the transaction and re-throw back to the developer.
		try {
			call_user_func($callback);
		}
		catch(PDOException $e) {
			$this->pdo->rollBack();

			throw $e;
		}

		$this->pdo->commit();
	}

	public function query($sql, $bindings = array()) {
		$sql = trim($sql);

		list($statement, $result) = $this->execute($sql, $bindings);

		// The result we return depends on the type of query executed against the
		// database. On SELECT clauses, we will return the result set, for update
		// and deletes we will return the affected row count.
		if(stripos($sql, 'select') === 0 or stripos($sql, 'show') === 0) {
			return $this->fetch($statement, Config::get('database.fetch'));
		}
		elseif(stripos($sql, 'update') === 0 or stripos($sql, 'delete') === 0) {
			return $statement->rowCount();
		}
		// For insert statements that use the "returning" clause, which is allowed
		// by database systems such as Postgres, we need to actually return the
		// real query result so the consumer can get the ID.
		elseif (stripos($sql, 'insert') === 0 and stripos($sql, 'returning') !== false) {
			return $this->fetch($statement, Config::get('database.fetch'));
		}
		else {
			return $result;
		}
	}

	public function type($var) {
		if(is_null($var)) {
			return PDO::PARAM_NULL;
		}

		if(is_int($var)) {
			return PDO::PARAM_INT;
		}

		if(is_bool($var)) {
			return PDO::PARAM_BOOL;
		}

		return PDO::PARAM_STR;
	}

	public function execute($sql, $bindings = array()) {
		// Each database operation is wrapped in a try / catch so we can wrap
		// any database exceptions in our custom exception class, which will
		// set the message to include the SQL and query bindings.
		try {
			$statement = $this->pdo->prepare($sql);

			// bind paramaters by data type
			// test key to see if we have to bump the index by one
			$zerobased = (strpos(key($bindings), ':') === 0) ? false : true;

			foreach($bindings as $index => $bind) {
				$key = $zerobased ? ($index + 1) : $index;

				$statement->bindValue($key, $bind, $this->type($bind));
			}

			$start = microtime(true);

			$result = $statement->execute();

			$this->queries[] = array($statement->queryString, $bindings);
		}
		// If an exception occurs, we'll pass it into our custom exception
		// and set the message to include the SQL and query bindings so
		// debugging is much easier on the developer.
		catch(PDOException $exception) {
			$message = explode(':', $exception->getMessage());

			$error = '<strong>Database Error:</strong>' . end($message) . str_repeat("\n", 3) .
				'<strong>SQL: </strong>' . $sql;

			$exception = new Exception($error, 0, $exception);

			throw $exception;
		}

		return array($statement, $result);
	}

	protected function fetch($statement, $style) {
		// If the fetch style is "class", we'll hydrate an array of PHP
		// stdClass objects as generic containers for the query rows,
		// otherwise we'll just use the fetch style value.
		if($style === PDO::FETCH_CLASS) {
			return $statement->fetchAll(PDO::FETCH_CLASS, 'stdClass');
		}
		else {
			return $statement->fetchAll($style);
		}
	}

}