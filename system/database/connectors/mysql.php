<?php namespace System\database\connectors;

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
use PDOException;
use ErrorException;
use System\Database\Connector;

class mysql extends Connector
{

    /**
     * Holds the php pdo instance
     *
     * @var object
     */
    private $pdo;

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
    public function __construct($config)
    {
        try {
            extract($config);

            $dns = 'mysql:' . implode(';', isset($database) ? array('dbname=' . $database, 'host=' . $hostname, 'port=' . $port, 'charset=' . $charset) : array('host=' . $hostname, 'port=' . $port, 'charset=' . $charset));
            $this->pdo = new PDO($dns, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * Return the pdo instance
     *
     * @param object PDO Object
     */
    public function instance()
    {
        return $this->pdo;
    }
}