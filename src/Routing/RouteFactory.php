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
     * @var \Zapheus\Contract\Http\Server\Middleware[]
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
        $this->handler = $route->getHandler();

        $this->method = $route->getMethod();

        $this->middlewares = $route->getMiddlewares();

        $this->parameters = $route->getParams();

        $this->uri = $route->getUri();

        return $this;
    }

    /**
     * @param \Zapheus\Contract\Http\Server\Middleware $middleware
     *
     * @return self
     */
    public function addMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;

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
     * @param array<class-string, string>|callable|string $handler
     *
     * @return self
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * @param string $method
     *
     * @return self
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param \Zapheus\Contract\Http\Server\Middleware[] $middlewares
     *
     * @return self
     */
    public function setMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware)
        {
            $this->addMiddleware($middleware);
        }

        return $this;
    }

    /**
     * @param array<string, string> $parameters
     *
     * @return self
     */
    public function setParams($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $uri
     *
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }
}
