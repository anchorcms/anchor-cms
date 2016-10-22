<?php

namespace Anchorcms\Plugins;

use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class AbstractPlugin
{
    abstract public function getSubscribedEvents(EventDispatcher $dispatcher);
}
