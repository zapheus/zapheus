<?php

namespace Zapheus\Http;

use Zapheus\Application;
use Zapheus\Container\WritableInterface;
use Zapheus\Http\Server\Dispatcher;
use Zapheus\Provider\ProviderInterface;

/**
 * Server Provider
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ServerProvider implements ProviderInterface
{
    /**
     * @var \Zapheus\Http\Server\MiddlewareInterface[]
     */
    protected $middlewares = array();

    /**
     * Initializes the middleware instance.
     *
     * @param \Zapheus\Http\Server\MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares = array())
    {
        $this->middlewares = $middlewares;
    }

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Container\WritableInterface $container
     *
     * @return \Zapheus\Container\ContainerInterface
     */
    public function register(WritableInterface $container)
    {
        $interface = Application::MIDDLEWARE;

        $config = $container->get(ProviderInterface::CONFIG);

        $items = (array) $this->middlewares;

        $middlewares = $config->get('app.middlewares', $items);

        $dispatcher = new Dispatcher($middlewares, $container);

        return $container->set($interface, $dispatcher);
    }
}
