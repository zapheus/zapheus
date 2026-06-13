<?php

namespace Zapheus\Fixture\Http\Middlewares;

use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Http\Server\Middleware;
use Zapheus\Http\Factory\Response;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class JsonMiddleware implements Middleware
{
    /**
     * @param \Zapheus\Contract\Http\Message\Request $request
     * @param \Zapheus\Contract\Http\Server\Handler  $handler
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function process(Request $request, Handler $handler)
    {
        $response = $handler->handle($request);

        $content = $response->getHeader('Content-Type');

        $value = array('application/json');

        $factory = new Response;

        $factory = $factory->setResponse($response);

        $factory->withHeader('Content-Type', $value);

        return count($content) >= 1 ? $response : $factory->make();
    }
}
