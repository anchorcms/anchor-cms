<?php
namespace Anchorcms\Events;

use Pimple\Container;
use Symfony\Component\EventDispatcher\Event;
use Tari\Server;
use Tari\ServerMiddlewareInterface;

/**
 * Class MiddlewareEvent.
 * @package Anchorcms\Events
 */
class MiddlewareEvent extends Event
{
    /**
     * the container to be used in middlewares
     *
     * @access protected
     * @var Container
     */
    protected $container;

    /**
     * all middlewares that will be prepended to the stack.
     *
     * @access protected
     * @var array
     */
    protected $prepends = [];

    /**
     * all middlewares that will be appended to the stack.
     *
     * @access protected
     * @var array
     */
    protected $appends = [];

    /**
     * MiddlewareEvent constructor.
     *
     * @param $app
     * @constructor
     */
    public function __construct(Container $app)
    {
        $this->container = $app;
    }

    /**
     * prepends or appends all plugin specified middlewares to the server
     *
     * @access public
     * @param Server $server
     * @return Server
     */
    public function addMiddlewares(Server $server): Server
    {
        foreach ($this->prepends as $middleware) {
            $server->prepend($middleware);
        }

        foreach ($this->appends as $middleware) {
            $server->append($middleware);
        }

        return $server;
    }

    /**
     * returns the app container
     *
     * @access public
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * stores the middleware to prepend to the server
     *
     * @access public
     * @param ServerMiddlewareInterface $middleware
     * @return MiddlewareEvent
     */
    public function prepend(ServerMiddlewareInterface $middleware): MiddlewareEvent
    {
        array_push($this->prepends, $middleware);

        return $this;
    }

    /**
     * stores the middleware to append to the server
     *
     * @access public
     * @param ServerMiddlewareInterface $middleware
     * @return MiddlewareEvent
     */
    public function append(ServerMiddlewareInterface $middleware): MiddlewareEvent
    {
        array_push($this->appends, $middleware);

        return $this;
    }
}
