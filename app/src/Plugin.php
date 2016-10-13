<?php

namespace Anchorcms;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Abstract plugin
 *
 * @package Anchorcms
 */
abstract class Plugin
{
    /**
     * retrieves the event dispatcher to attach plugin event listeners
     *
     * @access public
     * @param EventDispatcher $dispatcher
     * @return mixed
     */
    abstract public function getSubscribedEvents(EventDispatcher $dispatcher);
}
