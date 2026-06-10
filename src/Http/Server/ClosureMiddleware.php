<?php

namespace Zapheus\Http\Server;

use Zapheus\Http\Message\RequestInterface;

/**
 * Closure Middleware
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ClosureMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Initializes the middleware instance.
     *
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Processes an incoming request and returns a response.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     * @param \Zapheus\Http\Server\HandlerInterface  $handler
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function process(RequestInterface $request, HandlerInterface $handler)
    {
        $middleware = $this->callback;

        return $middleware($request, $handler);
    }
}
