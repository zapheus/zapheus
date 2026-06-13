<?php

namespace Zapheus\Routing;

use Zapheus\Contract\Routing\Dispatcher as Contract;
use Zapheus\Contract\Routing\Router as RouterContract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements Contract
{
    /**
     * @var \Zapheus\Contract\Routing\Router
     */
    protected $router;

    /**
     * Initializes the dispatcher instance.
     *
     * @param \Zapheus\Contract\Routing\Router $router
     */
    public function __construct(RouterContract $router)
    {
        $this->router = $router;
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return \Zapheus\Contract\Routing\Route
     * @throws \UnexpectedValueException
     */
    public function dispatch($method, $uri)
    {
        $uri = $uri[0] !== '/' ? '/' . $uri : $uri;

        if (($result = $this->match($method, $uri)) === null)
        {
            $text = 'Route "%s %s" not found';

            $error = sprintf($text, $method, $uri);

            throw new \UnexpectedValueException($error);
        }

        $matches = $result[0];

        $route = $result[1];

        $filtered = array_filter(array_keys($matches), 'is_string');

        $flipped = array_flip($filtered);

        $values = array_intersect_key($matches, $flipped);

        $factory = new RouteFactory;

        $factory = $factory->setRoute($route);

        return $factory->parameters($values)->make();
    }

    /**
     * Matches the route from the parsed URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return array{0: array<string>, 1: \Zapheus\Contract\Routing\Route}|null
     */
    protected function match($method, $uri)
    {
        $result = null;

        foreach ($this->router->routes() as $route)
        {
            $matched = preg_match($route->regex(), $uri, $matches);

            if ($matched && $route->method() === $method)
            {
                return array($matches, $route);
            }
        }

        return $result;
    }
}
