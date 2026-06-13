<?php

namespace Zapheus\Routing;

use Zapheus\Contract\Http\Server\Middleware;
use Zapheus\Contract\Routing\Route as Contract;

class RouteFactory
{
    /**
     * @var array<class-string, string>|callable|string
     */
    protected $handler;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array<int, \Zapheus\Contract\Http\Server\Middleware>
     */
    protected $middlewares = array();

    /**
     * @var array<string, string>
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $uri;

    /**
     * Sets the route instance and copies its properties.
     *
     * @param \Zapheus\Contract\Routing\Route $route
     *
     * @return self
     */
    public function setRoute(Contract $route)
    {
        $this->handler = $route->handler();

        $this->method = $route->method();

        $this->middlewares = $route->middlewares();

        $this->parameters = $route->parameters();

        $this->uri = $route->uri();

        return $this;
    }

    /**
     * @param array<class-string, string>|callable|string $handler
     *
     * @return self
     */
    public function handler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * @return \Zapheus\Routing\Route
     */
    public function make()
    {
        return new Route($this->method, $this->uri, $this->handler, $this->middlewares, $this->parameters);
    }

    /**
     * @param string $method
     *
     * @return self
     */
    public function method($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param \Zapheus\Contract\Http\Server\Middleware $middleware
     *
     * @return self
     */
    public function middleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware> $middlewares
     *
     * @return self
     */
    public function middlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware)
        {
            $this->middleware($middleware);
        }

        return $this;
    }

    /**
     * @param array<string, string> $parameters
     *
     * @return self
     */
    public function parameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $uri
     *
     * @return self
     */
    public function uri($uri)
    {
        $this->uri = $uri;

        return $this;
    }
}
