<?php

namespace Zapheus\Http\Server;

use Zapheus\Http\Message\RequestInterface;

/**
 * Handler Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface HandlerInterface
{
    /**
     * Handles the request and returns a response.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function handle(RequestInterface $request);
}
