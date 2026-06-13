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
class NextHandler implements Handler
{
    /**
     * @var \Zapheus\Contract\Http\Server\Middleware
     */
    protected $middleware;

    /**
     * @var \Zapheus\Contract\Http\Server\Handler
     */
    protected $handler;

    /**
     * @param \Zapheus\Contract\Http\Server\Middleware $middleware
     * @param \Zapheus\Contract\Http\Server\Handler    $handler
     */
    public function __construct(Middleware $middleware, Handler $handler)
    {
        $this->middleware = $middleware;

        $this->handler = $handler;
    }

    /**
     * Handles the request and returns a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function __invoke(Request $request)
    {
        return $this->handle($request);
    }

    /**
     * Handles the request and returns a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function handle(Request $request)
    {
        return $this->middleware->process($request, $this->handler);
    }
}
