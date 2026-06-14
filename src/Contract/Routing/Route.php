<?php

namespace Zapheus\Contract\Routing;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Route
{
    const ALLOWED_REGEX = '[a-zA-Z0-9\_\-]+';

    /**
     * Returns the handler.
     *
     * @return array<class-string, string>|callable|string
     */
    public function getHandler();

    /**
     * Returns the HTTP method.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Returns an array of middlewares.
     *
     * @return \Zapheus\Contract\Http\Server\Middleware[]
     */
    public function getMiddlewares();

    /**
     * Returns the parameters if any.
     *
     * @return array<string, string>
     */
    public function getParams();

    /**
     * Returns a regular expression from URI.
     *
     * @return string
     */
    public function getRegex();

    /**
     * Returns the URI.
     *
     * @return string
     */
    public function getUri();
}
