<?php

namespace Zapheus\Http\Factory;

use Zapheus\Contract\Http\Message\Uri as Contract;
use Zapheus\Http\Message\Uri as Base;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Uri
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
    protected $path = '';

    /**
     * @var string|null
     */
    protected $pass = null;

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
    protected $user = '';

    /**
     * Sets the URI instance and copies its properties.
     *
     * @param \Zapheus\Contract\Http\Message\Uri $uri
     *
     * @return self
     */
    public function setUri(Contract $uri)
    {
        $this->fragment = $uri->getFragment();

        $this->host = $uri->getHost();

        $this->path = $uri->getPath();

        $this->port = $uri->getPort();

        $this->query = $uri->getQuery();

        $this->scheme = $uri->getScheme();

        $this->user = $uri->getUserInfo();

        return $this;
    }

    /**
     * Creates the URI instance.
     *
     * @return \Zapheus\Contract\Http\Message\Uri
     */
    public function make()
    {
        $authority = $this->host;

        $fragment = $this->fragment;

        $query = $this->query;

        if ($this->host !== '' && $this->user !== null)
        {
            $user = $this->user;

            if ($this->pass)
            {
                $user = $this->user . ':' . $this->pass;
            }

            $authority = $user . '@' . $authority;

            $authority = $authority . ':' . $this->port;
        }

        if ($query)
        {
            $query = '?' . $query;
        }

        if ($fragment)
        {
            $fragment = '#' . $fragment;
        }

        return new Base($this->scheme . '://' . $authority . $this->path . $query . $fragment);
    }

    /**
     * Return an instance with the specified fragment.
     *
     * @param string $fragment
     *
     * @return self
     */
    public function withFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Return an instance with the specified host.
     *
     * @param string $host
     *
     * @return self
     */
    public function withHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Return an instance with the specified path.
     *
     * @param string $path
     *
     * @return self
     */
    public function withPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Return an instance with the specified port.
     *
     * @param integer $port
     *
     * @return self
     */
    public function withPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Return an instance with the specified query string.
     *
     * @param string $query
     *
     * @return self
     */
    public function withQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * @param string $scheme
     *
     * @return self
     */
    public function withScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param string      $user
     * @param string|null $password
     *
     * @return self
     */
    public function withUserInfo($user, $password = null)
    {
        $this->user = $user;

        $this->pass = $password;

        return $this;
    }
}
