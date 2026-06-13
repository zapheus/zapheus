<?php

namespace Zapheus\Contract\Http\Server;

use Zapheus\Contract\Http\Message\Request;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Dispatcher extends Middleware
{
    /**
     * Dispatches the defined middleware stack.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function dispatch(Request $request);

    /**
     * Adds a new middleware to the stack.
     *
     * @param mixed $middleware
     *
     * @return self
     */
    public function pipe($middleware);
}
