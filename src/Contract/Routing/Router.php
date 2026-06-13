<?php

namespace Zapheus\Contract\Routing;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Router
{
    /**
     * Adds a new Route to the collection.
     *
     * @param \Zapheus\Contract\Routing\Route $route
     *
     * @return self
     */
    public function add(Route $route);

    /**
     * Returns an array of routes.
     *
     * @return \Zapheus\Contract\Routing\Route[]
     */
    public function routes();
}
