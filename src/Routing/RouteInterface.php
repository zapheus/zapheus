<?php

namespace Zapheus\Routing;

/**
 * Route Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface RouteInterface
{
    const ALLOWED_REGEX = '[a-zA-Z0-9\_\-]+';

    /**
     * Returns the handler.
     *
     * @return array|callable|string
     */
    public function handler();

    /**
     * Returns the HTTP method.
     *
     * @return string
     */
    public function method();

    /**
     * Returns an array of middlewares.
     *
     * @return array
     */
    public function middlewares();

    /**
     * Returns the parameters if any.
     *
     * @return array
     */
    public function parameters();

    /**
     * Returns a regular expression from URI.
     *
     * @return string
     */
    public function regex();

    /**
     * Returns the URI.
     *
     * @return string
     */
    public function uri();
}
