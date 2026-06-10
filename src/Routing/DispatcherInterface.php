<?php

namespace Zapheus\Routing;

/**
 * Route Dispatcher Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface DispatcherInterface
{
    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return \Zapheus\Routing\RouteInterface
     * @throws \UnexpectedValueException
     */
    public function dispatch($method, $uri);
}
