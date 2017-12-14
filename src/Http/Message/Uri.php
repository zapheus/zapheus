<?php

namespace Slytherium\Http\Message;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * URI
 *
 * @package Slytherium
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Uri implements UriInterface
{
    /**
     * @var string
     */
    protected $scheme = '';

    /**
     * @var string
     */
    protected $user = '';

    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var integer|null
     */
    protected $port = null;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $query = '';

    /**
     * @var string
     */
    protected $fragment = '';

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        $parts = parse_url($uri) ?: array();

        foreach ($parts as $key => $value) {
            $key === 'user' && $this->user = $value;

            $this->$key = $value;
        }

        $this->uri = $uri;
    }

    /**
     * Return the string representation as a URI reference.
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

        if (! empty($this->host) && ! empty($this->user)) {
            $authority = $this->user . '@' . $authority;

            $authority .= ':'. $this->port;
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
     * @return null|integer
     */
    public function getPort()
    {
        return $this->port;
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
        return $this->user;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * @param  string $fragment
     * @return static
     */
    public function withFragment($fragment)
    {
        $new = clone $this;

        $new->fragment = $fragment;

        return $new;
    }

    /**
     * Return an instance with the specified host.
     *
     * @param  string $host
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withHost($host)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->host = $host;

        return $new;
    }

    /**
     * Return an instance with the specified path.
     *
     * @param  string $path
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withPath($path)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * Return an instance with the specified port.
     *
     * @param  null|integer $port
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withPort($port)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->port = $port;

        return $new;
    }

    /**
     * Return an instance with the specified query string.
     *
     * @param  string $query
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withQuery($query)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * @param  string $scheme
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withScheme($scheme)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->scheme = $scheme;

        return $new;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param  string      $user
     * @param  null|string $password
     * @return static
     */
    public function withUserInfo($user, $password = null)
    {
        $new = clone $this;

        $new->user = $user . ':' . $password;

        return $new;
    }
}
