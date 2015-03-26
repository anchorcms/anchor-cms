<?php

namespace System\Database\Connectors;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package        nano
 * @link        http://madebykieron.co.uk
 * @copyright    http://unlicense.org/
 */

use PDO;
use PDOException;
use ErrorException;
use System\Database\Connector;

class Pgsql extends Connector
{
    /**
     * Holds the php pdo instance
     *
     * @var PDO
     */
    private $pdo;

    /**
     * The pgsql left wrapper
     *
     * @var string
     */
    public $lwrap = '`';

    /**
     * The pgsql right wrapper
     *
     * @var string
     */
    public $rwrap = '`';


    /**
     * Create a new pgsql connector
     *
     * @param $config
     * @throws ErrorException
     */
    public function __construct($config)
    {
        try {
            extract($config);

            $dns = 'pgsql:' . implode(';', array('dbname=' . $database, 'host=' . $hostname, 'port=' . $port));
            $this->pdo = new PDO($dns, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * @return PDO
     */
    public function instance()
    {
        return $this->pdo;
    }

}