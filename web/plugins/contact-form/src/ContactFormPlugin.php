<?php

namespace RockyRoad;

use Anchorcms\Plugins\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class ContactFormPlugin extends AbstractPlugin
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
