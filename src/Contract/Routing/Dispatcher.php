<?php

namespace Zapheus\Contract\Routing;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Dispatcher
{
    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return \Zapheus\Contract\Routing\Route
     * @throws \UnexpectedValueException
     */
    public function dispatch($method, $uri);
}
