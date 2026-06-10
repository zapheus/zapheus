<?php

namespace Zapheus\Fixture\Http\Middlewares;

use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseFactory;
use Zapheus\Http\Server\HandlerInterface;
use Zapheus\Http\Server\MiddlewareInterface;

/**
 * JSON Middleware
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class JsonMiddleware implements MiddlewareInterface
{
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
        $response = $handler->handle($request);

        $content = $response->header('Content-Type');

        $value = array('application/json');

        $factory = new ResponseFactory($response);

        $factory->header('Content-Type', $value);

        return count($content) >= 1 ? $response : $factory->make();
    }
}
