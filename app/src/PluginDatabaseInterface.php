<?php


namespace Anchorcms;


use Doctrine\DBAL\Connection;

interface PluginDatabaseInterface
{
    public function getDatabaseConnection(Connection $database, string $prefix);
}
