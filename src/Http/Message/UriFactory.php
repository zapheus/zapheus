<?php

namespace Zapheus\Http\Message;

use Zapheus\Contract\Http\Message\Uri as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UriFactory
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
    protected $uri = '';

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
        $this->fragment = $uri->fragment();

        $this->host = $uri->host();

        $this->path = $uri->path();

        $this->port = $uri->port();

        $this->query = $uri->query();

        $this->scheme = $uri->scheme();

        $this->user = $uri->user();

        return $this;
    }

    /**
     * Sets the fragment component.
     *
     * @param string $fragment
     *
     * @return self
     */
    public function fragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Sets the host component.
     *
     * @param string $host
     *
     * @return self
     */
    public function host($host)
    {
        $this->host = $host;

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

        return new Uri($this->scheme . '://' . $authority . $this->path . $query . $fragment);
    }

    /**
     * Sets the path component.
     *
     * @param string $path
     *
     * @return self
     */
    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Sets the port component.
     *
     * @param integer $port
     *
     * @return self
     */
    public function port($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Sets the query component.
     *
     * @param string $query
     *
     * @return self
     */
    public function query($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Sets the scheme component.
     *
     * @param string $scheme
     *
     * @return self
     */
    public function scheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Sets the user component.
     *
     * @param string      $user
     * @param string|null $pass
     *
     * @return self
     */
    public function user($user, $pass = null)
    {
        if ($pass)
        {
            $this->pass = $pass;
        }

        $this->user = $user;

        return $this;
    }
}
