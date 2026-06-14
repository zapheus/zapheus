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
     * @var \Zapheus\Contract\Http\Server\Middleware[]
     */
    protected $middlewares = array();

    /**
     * @var array<string, string>
     */
    protected $params = array();

    /**
     * @var string
     */
    protected $uri;

    /**
     * @param string                                      $method
     * @param string                                      $uri
     * @param array<class-string, string>|callable|string $handler
     * @param \Zapheus\Contract\Http\Server\Middleware[]  $middlewares
     * @param array<string, string>                       $params
     */
    public function __construct($method, $uri, $handler, $middlewares = array(), $params = array())
    {
        $this->handler = $handler;

        $this->method = $method;

        $this->middlewares = $middlewares;

        $this->params = $params;

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
     * @return \Zapheus\Contract\Http\Server\Middleware[]
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
        return $this->params;
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
        // Turn "(/)" into "/?" -------------------------
        /** @var string */
        $uri = preg_replace('#\(/\)#', '/?', $this->uri);
        // ----------------------------------------------

        $regex = self::ALLOWED_REGEX;

        // Capture '{parameter}' group" --------------------
        $uri = $this->capture($uri, '/{(' . $regex . ')}/');
        // -------------------------------------------------

        // Capture ":parameter" group ---------------------
        $uri = $this->capture($uri, '/:(' . $regex . ')/');
        // ------------------------------------------------

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
     * @return string
     */
    protected function capture($pattern, $search)
    {
        $regex = '(?<$1>' . self::ALLOWED_REGEX . ')';

        /** @var string */
        return preg_replace($search, $regex, $pattern);
    }
}
