<?php

namespace Zapheus\Routing;

use Zapheus\Contract\Routing\Route as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Route implements Contract
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
     * @var array<mixed>
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
     * @param string                                                                   $method
     * @param string                                                                   $uri
     * @param array<class-string, string>|callable|string                              $handler
     * @param array<integer, \Zapheus\Contract\Http\Server\Middleware>|callable|string $middlewares
     * @param array<string, string>                                                    $parameters
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
     * @return array<class-string, string>|callable|string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Returns the HTTP method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns an array of middlewares.
     *
     * @return array<integer, \Zapheus\Contract\Http\Server\Middleware>
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Returns the parameters if any.
     *
     * @return array<string, string>
     */
    public function getParams()
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
    public function getRegex()
    {
        // Turn "(/)" into "/?"
        $uri = preg_replace('#\(/\)#', '/?', $this->uri);

        // Create capture group for ":parameter", replaces ":parameter"
        $uri = $this->capture($uri, '/:(' . self::ALLOWED_REGEX . ')/');

        // Create capture group for '{parameter}', replaces "{parameter}"
        $uri = $this->capture($uri, '/{(' . self::ALLOWED_REGEX . ')}/');

        // Add start and end matching
        return '@^' . $uri . '$@D';
    }

    /**
     * Returns the URI.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Capture the specified regular expressions.
     *
     * @param string $pattern
     * @param string $search
     *
     * @return string|null
     */
    protected function capture($pattern, $search)
    {
        $replace = '(?<$1>' . self::ALLOWED_REGEX . ')';

        return preg_replace($search, $replace, $pattern);
    }
}
