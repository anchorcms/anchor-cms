<?php

namespace Anchorcms\Plugins\ContactFormPlugin;

use Anchorcms\Events\Admin\BeforeRenderEvent;
use Anchorcms\Events\FilterEvent;
use Anchorcms\Plugin as AnchorPlugin;
use Anchorcms\PluginDatabaseInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Anchorcms\Events\RoutingEvent;
use \Doctrine\DBAL\Connection;

class Plugin extends AnchorPlugin implements PluginDatabaseInterface
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
        $dispatcher->addListener('filters', [$this, 'insertContactForm']);
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

    public function getDatabaseConnection(Connection $database, string $prefix)
    {
        $this->mappers['settings'] = new Mappers\Mapper($database, new Models\Settings);
        $this->mappers['settings']->setTablePrefix($prefix);
    }

    /**
     * add the contact form filter
     *
     * @access public
     * @param FilterEvent $filters
     *
     * @return void
     */
    public function insertContactForm(FilterEvent $filters)
    {
        $filters->addFilter('contactForm', [$this, 'createContactForm']);
    }

    /**
     * creates the HTML for a contact form
     *
     * @access public
     * @param $request
     * @param $variables
     *
     * @return string
     */
    public function createContactForm($request, $variables)
    {
        // if we have no ID, we cannot insert a contact form
        if (!key_exists('id', $variables)) return '';

        $contactForm = '<form id="contact-form-' . $variables['id'] . '">';
        $contactForm .= '<h3>Contact Form ' . $variables['id'] . '</h3>';
        $contactForm .= '</form>';

        return $contactForm;
    }
}
