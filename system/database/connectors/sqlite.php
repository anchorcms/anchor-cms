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
	 * Holds the php pdo instance
	 *
	 * @var object
	 */
	private $pdo;

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
	public function __construct($config) {
		extract($config);

		$dns = 'sqlite:' . $database;
		$this->pdo = new PDO($dns);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Return the pdo instance
	 *
	 * @param object PDO Object
	 */
	public function instance() {
		return $this->pdo;
	}

}