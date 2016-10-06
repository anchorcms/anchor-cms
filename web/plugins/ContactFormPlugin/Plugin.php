<?php

namespace Anchorcms\Plugins\ContactFormPlugin;

use Anchorcms\Events\Admin\BeforeRenderEvent;
use Anchorcms\Plugin as AnchorPlugin;
use Anchorcms\PluginUsingDatabaseInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Anchorcms\Events\RoutingEvent;
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
        $dispatcher->addListener('admin:beforeLayoutRender', [$this, 'addMenuItem']);
    }

    public function onRoutes(RoutingEvent $routes)
    {
        $routes->appends([
            '/admin/forms' => 'plugins\\contactFormPlugin\\controllers\\forms@index',
            '/admin/forms/create' => 'plugins\\contactFormPlugin\\controllers\\forms@create',
            '/admin/forms/:id' => 'plugins\\contactFormPlugin\\controllers\\forms@edit',
        ]);
    }

    public function addMenuItem(BeforeRenderEvent $event)
    {
        $menuItems = $event->getVar('additionalMenuItems');
        $menuItems['Contact Forms'] = '/admin/forms';
        $event->setVar('additionalMenuItems', $menuItems);
    }

    public function setupPluginMappers(Connection $database, string $prefix)
    {
        $this->mappers['settings'] = new Mappers\Mapper($database, new Models\Settings);
        $this->mappers['settings']->setTablePrefix($prefix);
    }
}
