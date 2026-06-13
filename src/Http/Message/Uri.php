<?php

namespace Zapheus\Http\Message;

use Zapheus\Contract\Http\Message\Uri as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Uri implements Contract
{
    /**
     * @var string
     */
    protected $fragment = '';

    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var string
     */
    protected $pass = null;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var integer|string|null
     */
    protected $port = null;

    /**
     * @var string
     */
    protected $query = '';

    /**
     * @var string
     */
    protected $scheme = '';

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var string
     */
    protected $user = '';

    /**
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        $parts = parse_url($uri) ?: array();

        foreach ($parts as $key => $value)
        {
            $this->$key = $value;
        }

        $this->uri = $uri;
    }

    /**
     * Returns the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->uri;
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string
     */
    public function getAuthority()
    {
        $authority = $this->host;

        if ($this->host !== '' && $this->user !== null)
        {
            $authority = $this->user . '@' . $authority;

            $authority = $authority . ':' . $this->port;
        }

        return $authority;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Retrieve the path component of the URI.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * @return integer|null
     */
    public function getPort()
    {
        return (int) $this->port;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Retrieve the scheme component of the URI.
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * @return string
     */
    public function getUserInfo()
    {
        if ($this->pass)
        {
            return $this->user . ':' . $this->pass;
        }

        return $this->user;
    }
}
