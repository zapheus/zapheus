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

        if (count($route->middlewares()) > 0)
        {
            $middlewares = $route->middlewares();

            $dispatcher = (new Dispatcher($middlewares))->container($this->container);

            return $dispatcher->process($request, $handler);
        }

        return $handler->handle($request);
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
        $route = $request->attribute(Application::ROUTE_ATTRIBUTE);

        if ($route instanceof Route)
        {
            return $route;
        }

        $dispatcher = $this->container->get(Application::DISPATCHER);

        $path = $request->uri()->path();

        return $dispatcher->dispatch($request->method(), $path);
    }
}
