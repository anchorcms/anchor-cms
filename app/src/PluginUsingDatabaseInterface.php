<?php


namespace Anchorcms;

use \Doctrine\DBAL\Connection;

interface PluginUsingDatabaseInterface
{
    /**
     * sets up the plugin's database mappers
     *
     * @access public
     *
     * @param Connection $database
     * @param string     $prefix
     *
     * @return mixed
     */
    public function setupPluginMappers(Connection $database, string $prefix);
}
