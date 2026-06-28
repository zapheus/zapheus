<?php

namespace Zapheus\Fixture\Http\Middlewares;

use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Http\Server\Middleware;
use Zapheus\Http\Factory\Request as RequestFactory;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SpoofMethod implements Middleware
{
    /**
     * @param \Zapheus\Contract\Http\Message\Request $request
     * @param \Zapheus\Contract\Http\Server\Handler  $handler
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function process(Request $request, Handler $handler)
    {
        /** @var array<string, string> */
        $body = $request->getParsedBody();

        if (array_key_exists('_method', $body))
        {
            $factory = new RequestFactory;

            $factory->setRequest($request);

            $factory->withMethod($body['_method']);

            $request = $factory->make();
        }

        return $handler->handle($request);
    }
}
