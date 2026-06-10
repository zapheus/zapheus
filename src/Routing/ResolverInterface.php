<?php

namespace Zapheus\Routing;

/**
 * Resolver Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface ResolverInterface
{
    /**
     * Resolves the specified route instance.
     *
     * @param \Zapheus\Routing\RouteInterface $route
     *
     * @return mixed
     */
    public function resolve(RouteInterface $route);
}
