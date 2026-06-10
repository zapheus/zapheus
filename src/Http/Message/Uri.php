<?php

namespace Zapheus\Http\Message;

/**
 * Uniform Resource Identifier (URI)
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Uri implements UriInterface
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
     * @var integer|null
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
     * Initializes the URI instance.
     *
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        $parts = parse_url($uri) ?: array();

        foreach ($parts as $key => $value)
        {
            $this->$key = (string) $value;
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
     * Returns the authority component of the URI.
     *
     * @return string
     */
    public function authority()
    {
        $authority = $this->host;

        if ($this->host !== '' && $this->user !== null)
        {
            $authority = $this->user . '@' . $authority;

            $authority = $authority . ':' . $this->port;
        }

        return $authority;

        // getAuthority
    }

    /**
     * Returns the fragment component of the URI.
     *
     * @return string
     */
    public function fragment()
    {
        return $this->fragment;

        // getFragment
        // withFragment
    }

    /**
     * Returns the host component of the URI.
     *
     * @return string
     */
    public function host()
    {
        return $this->host;

        // getHost
        // withHost
    }

    /**
     * Returns the path component of the URI.
     *
     * @return string
     */
    public function path()
    {
        return $this->path;

        // getPath
        // withPath
    }

    /**
     * Returns the port component of the URI.
     *
     * @return integer|null
     */
    public function port()
    {
        return (int) $this->port;

        // getPort
        // withPort
    }

    /**
     * Returns the query string of the URI.
     *
     * @return string
     */
    public function query()
    {
        return $this->query;

        // getQuery
        // withQuery
    }

    /**
     * Returns the scheme component of the URI.
     *
     * @return string
     */
    public function scheme()
    {
        return $this->scheme;

        // getScheme
        // withScheme
    }

    /**
     * Returns the user information component of the URI.
     *
     * @return string
     */
    public function user()
    {
        if ($this->pass)
        {
            return $this->user . ':' . $this->pass;
        }

        return $this->user;

        // getUserInfo
        // withUserInfo
    }
}
