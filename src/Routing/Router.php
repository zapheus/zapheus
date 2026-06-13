<?php

namespace Zapheus\Routing;

use Zapheus\Contract\Routing\Route as RouteContract;
use Zapheus\Contract\Routing\Router as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Router implements Contract
{
    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var \Zapheus\Contract\Routing\Route[]
     */
    protected $routes = array();

    /**
     * @param \Zapheus\Routing\Route[] $routes
     */
    public function __construct(array $routes = array())
    {
        $this->routes = $routes;
    }

    /**
     * Adds a new Route to the collection.
     *
     * @param \Zapheus\Contract\Routing\Route $route
     *
     * @return self
     */
    public function add(RouteContract $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Adds a new route instance in CONNECT HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function connect($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('CONNECT', $uri, $handler, $middlewares));
    }

    /**
     * Adds a new route instance in DELETE HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function delete($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('DELETE', $uri, $handler, $middlewares));
    }

    /**
     * Adds a new route instance in GET HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function get($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('GET', $uri, $handler, $middlewares));
    }

    /**
     * Checks if the router contains the specified route.
     *
     * @param \Zapheus\Routing\Route $route
     *
     * @return boolean
     */
    public function has(RouteContract $route)
    {
        return array_search($route, $this->routes) !== false;
    }

    /**
     * Adds a new route instance in HEAD HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function head($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('HEAD', $uri, $handler, $middlewares));
    }

    /**
     * Adds a new route instance in OPTIONS HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function options($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('OPTIONS', $uri, $handler, $middlewares));
    }

    /**
     * Adds a new route instance in PATCH HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function patch($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('PATCH', $uri, $handler, $middlewares));
    }

    /**
     * Adds a new route instance in POST HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function post($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('POST', $uri, $handler, $middlewares));
    }

    /**
     * Adds a new route instance in PURGE HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function purge($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('PURGE', $uri, $handler, $middlewares));
    }

    /**
     * Adds a new route instance in PUT HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function put($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('PUT', $uri, $handler, $middlewares));
    }

    /**
     * Returns an array of routes.
     *
     * @return \Zapheus\Routing\Route[]
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * Adds a new route instance in TRACE HTTP method.
     *
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return self
     */
    public function trace($uri, $handler, $middlewares = array())
    {
        return $this->add($this->route('TRACE', $uri, $handler, $middlewares));
    }

    /**
     * Prepares a new route instance.
     *
     * @param string                                                               $method
     * @param string                                                               $uri
     * @param callable|string                                                      $handler
     * @param array<int, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     *
     * @return \Zapheus\Routing\Route
     */
    protected function route($method, $uri, $handler, $middlewares)
    {
        if (is_string($handler) === true)
        {
            $namespace = $this->namespace;

            $namespace !== '' && $namespace .= '\\';

            $handler = $namespace . $handler;
        }

        return new Route($method, $uri, $handler, $middlewares);
    }
}
