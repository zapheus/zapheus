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
    protected $items = array();

    /**
     * @param \Zapheus\Contract\Http\Server\Middleware[] $items
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
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
        $class = Application::MIDDLEWARE;

        /** @var \Zapheus\Contract\Provider\Configuration */
        $config = $container->get(Provider::CONFIG);

        $items = $this->items;

        /** @var \Zapheus\Contract\Http\Server\Middleware[] */
        $items = $config->get('app.middlewares', $items);

        $dispatch = new Dispatcher($items);

        $dispatch = $dispatch->setContainer($container);

        return $container->set($class, $dispatch);
    }
}
