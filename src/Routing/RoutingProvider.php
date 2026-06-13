<?php

namespace Zapheus\Routing;

use Zapheus\Application;
use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Provider\Provider;
use Zapheus\Contract\Routing\Router as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingProvider implements Provider
{
    const DISPATCHER = Application::DISPATCHER;

    const ROUTER = Application::ROUTER;

    /**
     * @var \Zapheus\Contract\Routing\Router
     */
    protected $router;

    /**
     * Sets the router instance.
     *
     * @param \Zapheus\Contract\Routing\Router $router
     *
     * @return self
     */
    public function setRouter(Contract $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Contract\Container\Writable $container
     *
     * @return \Zapheus\Contract\Container\Container
     */
    public function register(Writable $container)
    {
        if ($container->has(self::ROUTER))
        {
            $router = $container->get(self::ROUTER);

            $dispatcher = new Dispatcher($router);

            return $container->set(self::DISPATCHER, $dispatcher);
        }

        $config = $container->get(self::CONFIG);

        $default = $this->router !== null ? $this->router : new Router;

        $router = $config->get('app.router', $default);

        if (is_string($router))
        {
            $router = $container->get($router);
        }

        $dispatcher = new Dispatcher($router);

        return $container->set(self::DISPATCHER, $dispatcher);
    }
}
