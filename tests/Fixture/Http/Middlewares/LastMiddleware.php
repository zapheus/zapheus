<?php

namespace Zapheus\Fixture\Http\Middlewares;

use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Http\Server\Middleware;
use Zapheus\Http\Message\ResponseFactory;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LastMiddleware implements Middleware
{
    /**
     * @param \Zapheus\Contract\Http\Message\Request $request
     * @param \Zapheus\Contract\Http\Server\Handler  $handler
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function process(Request $request, Handler $handler)
    {
        $factory = new ResponseFactory;

        return $factory->make();
    }
}
