<?php

namespace Zapheus\Http\Server;

use Zapheus\Application;
use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Routing\Route;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingHandler implements Handler
{
    /**
     * @var \Zapheus\Contract\Container\Writable
     */
    protected $container;

    /**
     * Initializes the handler instance.
     *
     * @param \Zapheus\Contract\Container\Writable $container
     */
    public function __construct(Writable $container)
    {
        $this->container = $container;
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function handle(Request $request)
    {
        $route = $this->dispatch($request);

        $handler = new ResolverHandler($this->container, $route);

        if (count($route->middlewares()) === 0)
        {
            return $handler->handle($request);
        }

        $items = $route->middlewares();

        $dispatch = new Dispatcher($items);

        $dispatch = $dispatch->container($this->container);

        return $dispatch->process($request, $handler);
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Routing\Route
     */
    protected function dispatch(Request $request)
    {
        $attr = Application::ROUTE_ATTRIBUTE;

        $route = $request->attribute($attr);

        if ($route instanceof Route)
        {
            return $route;
        }

        $class = Application::DISPATCHER;

        /** @var \Zapheus\Contract\Routing\Dispatcher */
        $dispatch = $this->container->get($class);

        $uri = $request->uri()->path();

        $method = $request->method();

        return $dispatch->dispatch($method, $uri);
    }
}
