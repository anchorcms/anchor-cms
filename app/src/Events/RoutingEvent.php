<?php
namespace Anchorcms\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class RoutingEvent.
 * @package Anchorcms\Events
 */
class RoutingEvent extends Event
{
    /**
     * the routes the event carries
     *
     * @access protected
     * @var
     */
    protected $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    /**
     * magic method to allow calling route collection methods on the event
     *
     * @access public
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->routes, $name], $arguments);
    }

    /**
     * returns the route collection
     *
     * @access public
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
