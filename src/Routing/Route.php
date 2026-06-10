<?php

namespace Zapheus\Routing;

/**
 * Route
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Route implements RouteInterface
{
    /**
     * @var array|callable|string
     */
    protected $handler;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $middlewares = array();

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $uri;

    /**
     * Initializes the route instance.
     *
     * @param string                $method
     * @param string                $uri
     * @param array|callable|string $handler
     * @param array|callable|string $middlewares
     * @param array                 $parameters
     */
    public function __construct($method, $uri, $handler, $middlewares = array(), $parameters = array())
    {
        if (! is_array($middlewares))
        {
            $middlewares = array($middlewares);
        }

        $this->handler = $handler;

        $this->method = $method;

        $this->middlewares = $middlewares;

        $this->parameters = $parameters;

        $this->uri = $uri[0] !== '/' ? '/' . $uri : $uri;
    }

    /**
     * Returns the handler.
     *
     * @return array|callable|string
     */
    public function handler()
    {
        return $this->handler;
    }

    /**
     * Returns the HTTP method.
     *
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Returns an array of middlewares.
     *
     * @return array
     */
    public function middlewares()
    {
        return $this->middlewares;
    }

    /**
     * Returns the parameters if any.
     *
     * @return array
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * Returns a regular expression pattern from the given URI.
     *
     * @link https://stackoverflow.com/q/30130913
     *
     * @return string
     */
    public function regex()
    {
        // Turn "(/)" into "/?"
        $uri = preg_replace('#\(/\)#', '/?', $this->uri);

        // Create capture group for ":parameter", replaces ":parameter"
        $uri = $this->capture($uri, '/:(' . self::ALLOWED_REGEX . ')/');

        // Create capture group for '{parameter}', replaces "{parameter}"
        $uri = $this->capture($uri, '/{(' . self::ALLOWED_REGEX . ')}/');

        // Add start and end matching
        return (string) '@^' . $uri . '$@D';
    }

    /**
     * Returns the URI.
     *
     * @return string
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * Capture the specified regular expressions.
     *
     * @param string $pattern
     * @param string $search
     *
     * @return string
     */
    protected function capture($pattern, $search)
    {
        $replace = '(?<$1>' . self::ALLOWED_REGEX . ')';

        return preg_replace($search, $replace, $pattern);
    }
}
