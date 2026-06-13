<?php

namespace Zapheus\Contract\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Uri
{
    /**
     * Returns the authority component of the URI.
     *
     * @return string
     */
    public function authority();

    /**
     * Returns the fragment component of the URI.
     *
     * @return string
     */
    public function fragment();

    /**
     * Returns the host component of the URI.
     *
     * @return string
     */
    public function host();

    /**
     * Returns the path component of the URI.
     *
     * @return string
     */
    public function path();

    /**
     * Returns the port component of the URI.
     *
     * @return integer|null
     */
    public function port();

    /**
     * Returns the query string of the URI.
     *
     * @return string
     */
    public function query();

    /**
     * Returns the scheme component of the URI.
     *
     * @return string
     */
    public function scheme();

    /**
     * Returns the user information component of the URI.
     *
     * @return string
     */
    public function user();
}
