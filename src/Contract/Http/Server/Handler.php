<?php

namespace Zapheus\Contract\Http\Server;

use Zapheus\Contract\Http\Message\Request;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Handler
{
    /**
     * Handles the request and returns a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function handle(Request $request);
}
