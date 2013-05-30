<?php namespace System\Database\Connectors;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use PDO;
use System\Database\Connector;

class Mysql extends Connector {

	/**
	 * The mysql left wrapper
	 *
	 * @var string
	 */
	public $lwrap = '`';

	/**
	 * The mysql right wrapper
	 *
	 * @var string
	 */
	public $rwrap = '`';

	/**
	 * Create a new mysql connector
	 *
	 * @param array
	 */
	protected function connect($config) {
		extract($config);

		$dns = 'mysql:' . implode(';', array('dbname=' . $database, 'host=' . $hostname, 'port=' . $port, 'charset=' . $charset));
		return new PDO($dns, $username, $password);
	}

}