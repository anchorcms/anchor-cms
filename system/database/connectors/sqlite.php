<?php

namespace System\database\connectors;

/**
 * Nano
 * Just another php framework
 *
 * @package    nano
 * @link       http://madebykieron.co.uk
 * @copyright  http://unlicense.org/
 */

use PDO;
use System\Database\Connector;

/**
 * sqlite class
 *
 * @package System\database\connectors
 */
class sqlite extends Connector
{

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
     * Holds the php pdo instance
     *
     * @var object
     */
    private $pdo;

    /**
     * Create a new sqlite connector
     *
     * @param array $config SQLite connection configuration data
     */
    public function __construct($config)
    {
        /** @var string $database */
        extract($config);

        $dns       = 'sqlite:' . $database;
        $this->pdo = new PDO($dns);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Return the pdo instance
     *
     * @return object|\PDO PDO object
     */
    public function instance()
    {
        return $this->pdo;
    }
}
