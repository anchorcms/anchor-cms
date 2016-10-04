<?php

namespace Anchorcms\Plugins\ContactFormPlugin;

use Anchorcms\Plugin as AnchorPlugin;
use Anchorcms\PluginUsingDatabaseInterface;
use Anchorcms\Events\RoutingEvent;
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
        $dispatcher->addListener('routing', [$this, 'onRoutes']);
    }

    public function onRoutes(RoutingEvent $routes)
    {
        $routes->appends([
            '/admin/forms' => 'plugins\\contactFormPlugin\\controllers\\forms@index',
            '/admin/forms/create' => 'plugins\\contactFormPlugin\\controllers\\forms@create',
            '/admin/forms/:id' => 'plugins\\contactFormPlugin\\controllers\\forms@edit',
        ]);
    }

    public function setupPluginMappers(Connection $database, string $prefix)
    {
        $this->mappers['settings'] = new Mappers\Mapper($database, new Models\Settings);
        $this->mappers['settings']->setTablePrefix($prefix);
    }
}
