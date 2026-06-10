<?php

namespace Zapheus\Routing;

/**
 * Router Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface RouterInterface
{
    /**
     * Adds a new RouteInterface to the collection.
     *
     * @param \Zapheus\Routing\RouteInterface $route
     *
     * @return self
     */
    public function add(RouteInterface $route);

    /**
     * Returns an array of routes.
     *
     * @return \Zapheus\Routing\Route[]
     */
    public function routes();
}
