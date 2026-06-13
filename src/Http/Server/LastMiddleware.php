<?php

namespace Zapheus\Http\Server;

use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Http\Server\Middleware;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LastMiddleware implements Middleware
{
    /**
     * @var \Zapheus\Contract\Http\Server\Handler
     */
    protected $handler;

    /**
     * @param \Zapheus\Contract\Http\Server\Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Processes an incoming request and returns a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     * @param \Zapheus\Contract\Http\Server\Handler  $handler
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function process(Request $request, Handler $handler)
    {
        return $this->handler->handle($request);
    }
}
