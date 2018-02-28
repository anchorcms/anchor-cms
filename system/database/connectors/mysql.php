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

use ErrorException;
use PDO;
use PDOException;
use System\Database\Connector;

/**
 * mysql class
 *
 * @package System\database\connectors
 */
class mysql extends Connector
{

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
     * Holds the php pdo instance
     *
     * @var object
     */
    private $pdo;

    /**
     * Create a new mysql connector
     *
     * @param array $config MySQL connection configuration data
     *
     * @throws \ErrorException
     */
    public function __construct($config)
    {
        try {
            /** @var string $database */
            /** @var string $hostname */
            /** @var int $port */
            /** @var string $charset */
            /** @var string $username */
            /** @var string $password */
            extract($config);

            $dns = ('mysql:' . implode(';', isset($database)
                    ? [
                        'dbname=' . $database,
                        'host=' . $hostname,
                        'port=' . $port,
                        'charset=' . $charset
                    ]
                    : [
                        'host=' . $hostname,
                        'port=' . $port,
                        'charset=' . $charset
                    ]
                ));

            $this->pdo = new PDO($dns, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new ErrorException($e->getMessage());
        }
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
