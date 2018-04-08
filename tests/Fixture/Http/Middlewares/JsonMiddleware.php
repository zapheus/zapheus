<?php

namespace Zapheus\Fixture\Http\Middlewares;

use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Server\HandlerInterface;
use Zapheus\Http\Server\MiddlewareInterface;

/**
 * JSON Middleware
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class JsonMiddleware implements MiddlewareInterface
{
    /**
     * Processes an incoming request and returns a response.
     *
     * @param  \Zapheus\Http\Message\RequestInterface $request
     * @param  \Zapheus\Http\Server\HandlerInterface  $handler
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function process(RequestInterface $request, HandlerInterface $handler)
    {
        $response = $handler->handle($request);

        $content = $response->header('Content-Type');

        $value = array('application/json');

        $new = $response->push('headers', $value, 'Content-Type');

        return count($content) >= 1 ? $response : $new;
    }
}
