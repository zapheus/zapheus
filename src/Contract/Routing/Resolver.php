<?php

namespace Zapheus\Contract\Routing;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Resolver
{
    /**
     * Resolves the specified route instance.
     *
     * @param \Zapheus\Contract\Routing\Route $route
     *
     * @return mixed
     */
    public function resolve(Route $route);
}
