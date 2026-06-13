<?php

namespace Zapheus\Contract\Http\Server;

use Zapheus\Contract\Http\Message\Request;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Middleware
{
    /**
     * Processes an incoming request and returns a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     * @param \Zapheus\Contract\Http\Server\Handler  $handler
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function process(Request $request, Handler $handler);
}
