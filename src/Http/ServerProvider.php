<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Provider\Provider;
use Zapheus\Http\Server\Dispatcher;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ServerProvider implements Provider
{
    /**
     * @var \Zapheus\Contract\Http\Server\Middleware[]
     */
    protected $middlewares = array();

    /**
     * @param \Zapheus\Contract\Http\Server\Middleware[] $middlewares
     */
    public function __construct(array $middlewares = array())
    {
        $this->middlewares = $middlewares;
    }

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Contract\Container\Writable $container
     *
     * @return \Zapheus\Contract\Container\Writable
     */
    public function register(Writable $container)
    {
        $interface = Application::MIDDLEWARE;

        $config = $container->get(Provider::CONFIG);

        $items = $this->middlewares;

        $middlewares = $config->get('app.middlewares', $items);

        $dispatch = new Dispatcher($middlewares);

        $dispatch = $dispatch->container($container);

        return $container->set($interface, $dispatch);
    }
}
