<?php
namespace Anchorcms\Plugins\Debugger;

use Anchorcms\Events\MiddlewareEvent;
use Anchorcms\Plugin as AnchorPlugin;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Plugin extends AnchorPlugin
{
    public function getSubscribedEvents(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener('middleware', [$this, 'loadDebugMiddleware']);
    }

    public function loadDebugMiddleware(MiddlewareEvent $middlewares)
    {
        $middlewares->prepend(new Middleware\Debug($middlewares->getContainer()));
    }
}
