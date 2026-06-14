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
    /**
     * @var \Zapheus\Contract\Routing\Router|null
     */
    protected $router = null;

    /**
     * Returns the router instance.
     *
     * @return \Zapheus\Contract\Routing\Router
     */
    public function getRouter()
    {
        if ($this->router)
        {
            return $this->router;
        }

        return new Router;
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
        $name = Application::ROUTER;

        if ($container->has($name))
        {
            /** @var \Zapheus\Contract\Routing\Router */
            $router = $container->get($name);

            $dispatch = new Dispatcher($router);

            $class = Application::DISPATCHER;

            return $container->set($class, $dispatch);
        }

        /** @var \Zapheus\Contract\Provider\Configuration */
        $config = $container->get(self::CONFIG);

        $default = $this->getRouter();

        /** @var \Zapheus\Contract\Routing\Router|string */
        $router = $config->get('app.router', $default);

        if (is_string($router))
        {
            /** @var \Zapheus\Contract\Routing\Router */
            $router = $container->get($router);
        }

        $dispatch = new Dispatcher($router);

        $class = Application::DISPATCHER;

        return $container->set($class, $dispatch);
    }

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
}
