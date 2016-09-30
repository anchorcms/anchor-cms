<?php

namespace Anchorcms\Plugins\ContactFormPlugin;

use Anchorcms\Plugin as AnchorPlugin;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class Plugin extends AnchorPlugin
{
    public function getSubscribedEvents(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener('routes', [$this, 'onRoutes']);
    }

    protected function onRoutes(Event $event)
    {
        $event->getRouter()->append('/test', []);
    }
}
