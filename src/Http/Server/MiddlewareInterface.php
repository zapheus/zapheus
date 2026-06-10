<?php

namespace Zapheus\Http\Server;

use Zapheus\Http\Message\RequestInterface;

/**
 * Middleware Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface MiddlewareInterface
{
    /**
     * Processes an incoming request and returns a response.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     * @param \Zapheus\Http\Server\HandlerInterface  $handler
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function process(RequestInterface $request, HandlerInterface $handler);
}
