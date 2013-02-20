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

use Exception;

abstract class Connector {

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
	public function ask($sql, $arguments = array()) {
		try {
			$statement = $this->instance()->prepare($sql);
			$result = $statement->execute($arguments);

			return array($result, $statement);
		}
		catch(Exception $e) {
			$error = 'Database Error: ' . $e->getMessage() . '</code></p><p><code>SQL: ' . trim($sql);
			throw new Exception($error, 0, $e);
		}
	}

}