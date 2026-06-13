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
     * Retrieve the scheme component of the URI.
     *
     * @return string
     */
    public function getScheme();

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string
     */
    public function getAuthority();

    /**
     * Retrieve the user information component of the URI.
     *
     * @return string
     */
    public function getUserInfo();

    /**
     * Retrieve the host component of the URI.
     *
     * @return string
     */
    public function getHost();

    /**
     * Retrieve the port component of the URI.
     *
     * @return integer|null
     */
    public function getPort();

    /**
     * Retrieve the path component of the URI.
     *
     * @return string
     */
    public function getPath();

    /**
     * Retrieve the query string of the URI.
     *
     * @return string
     */
    public function getQuery();

    /**
     * Retrieve the fragment component of the URI.
     *
     * @return string
     */
    public function getFragment();

    /**
     * Return the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString();
}
