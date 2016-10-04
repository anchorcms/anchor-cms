<?php

namespace Anchorcms\Plugins\ContactFormPlugin;

use Anchorcms\Plugin as AnchorPlugin;
use Anchorcms\PluginUsingDatabaseInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use \Doctrine\DBAL\Connection;

class Plugin extends AnchorPlugin implements PluginUsingDatabaseInterface
{
    /**
     * the plugins database mappers
     *
     * @access protected
     * @var array
     */
    protected $mappers = [];

    public function getSubscribedEvents(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener('routes', [$this, 'onRoutes']);
    }

    protected function onRoutes(Event $event)
    {
        $event->getRouter()->append('/test', []);
    }

    public function setupPluginMappers(Connection $database, string $prefix) {
        $this->mappers['settings'] = new Mappers\Mapper($database, new Models\Settings);
        $this->mappers['settings']->setTablePrefix($prefix);
    }
}
