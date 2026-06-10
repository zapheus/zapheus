<?php

namespace Zapheus\Routing;

use Zapheus\Application;
use Zapheus\Container\WritableInterface;
use Zapheus\Provider\ProviderInterface;

/**
 * Routing Provider
 *
 * @package App
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingProvider implements ProviderInterface
{
    const DISPATCHER = Application::DISPATCHER;

    const ROUTER = Application::ROUTER;

    /**
     * @var \Zapheus\Routing\RouterInterface
     */
    protected $router;

    /**
     * Initializes the provider instance.
     *
     * @param \Zapheus\Routing\RouterInterface|null $router
     */
    public function __construct(RouterInterface $router = null)
    {
        $this->router = $router === null ? new Router : $router;
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
        if ($container->has(self::ROUTER))
        {
            $router = $container->get(self::ROUTER);

            $dispatcher = new Dispatcher($router);

            return $container->set(self::DISPATCHER, $dispatcher);
        }

        $config = $container->get(self::CONFIG);

        $router = $config->get('app.router', $this->router);

        if (is_string($router))
        {
            $router = $container->get($router);
        }

        $dispatcher = new Dispatcher($router);

        return $container->set(self::DISPATCHER, $dispatcher);
    }
}
