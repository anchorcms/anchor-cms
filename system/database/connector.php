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

use PDOException;
use System\Config;

abstract class Connector {

	/**
	 * Log of all queries
	 *
	 * @var array
	 */
	private $queries = array();

	/**
	 * All connectors will implement a function to return the pdo instance
	 *
	 * @param object PDO Object
	 */
	abstract public function instance();

	/**
	 * A simple database query wrapper
	 *
	 * @param string
	 * @param array
	 * @return array
	 */
	public function ask($sql, $binds = array()) {
		try {
			if(Config::db('profiling')) {
				$this->queries[] = compact('sql', 'binds');
			}

			$statement = $this->instance()->prepare($sql);
			$result = $statement->execute($binds);

			return array($result, $statement);
		}
		catch(PDOException $e) {
			throw new Exception($sql, $e);
		}
	}

	/**
	 * Return the profile array
	 *
	 * @return array
	 */
	public function profile() {
		return $this->queries;
	}

	/**
	 * Magic method for calling methods on PDO instance
	 *
	 * @param string
	 * @param array
	 * @return mixed
	 */
	public static function __callStatic($method, $arguments) {
		return call_user_func_array(array($this->instance(), $method), $arguments);
	}

}