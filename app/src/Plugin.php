<?php

namespace Anchorcms;

use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class Plugin
{
    abstract public function getSubscribedEvents(EventDispatcher $dispatcher);
}
