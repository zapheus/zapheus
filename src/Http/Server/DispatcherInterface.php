<?php

namespace Zapheus\Http\Server;

use Zapheus\Http\Message\RequestInterface;

/**
 * Dispatcher Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface DispatcherInterface extends MiddlewareInterface
{
    /**
     * Dispatches the defined middleware stack.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function dispatch(RequestInterface $request);

    /**
     * Adds a new middleware to the stack.
     *
     * @param mixed $middleware
     *
     * @return self
     */
    public function pipe($middleware);
}
