<?php

namespace Anchorcms;

use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class Plugin
{
    abstract public function getSubscribedEvents(EventDispatcher $dispatcher);

    abstract public function getDatabaseConnection(Connection $connection, string $prefix);
}
