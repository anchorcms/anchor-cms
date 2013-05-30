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

class Sqlite extends Connector {

	/**
	 * The sqlite left wrapper
	 *
	 * @var string
	 */
	public $lwrap = '[';

	/**
	 * The sqlite right wrapper
	 *
	 * @var string
	 */
	public $rwrap = ']';

	/**
	 * Create a new sqlite connector
	 *
	 * @param array
	 */
	protected function connect($config) {
		extract($config);

		$dns = 'sqlite:' . $database;
		return new PDO($dns);
	}

}