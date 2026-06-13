<?php

namespace Zapheus\Http\Message;

use Zapheus\Contract\Http\Message\Request as Contract;
use Zapheus\Contract\Http\Message\Stream as StreamContract;
use Zapheus\Contract\Http\Message\Uri as UriContract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Request extends Message implements Contract
{
    /**
     * @var array<string, mixed>
     */
    protected $attributes = array();

    /**
     * @var array<string, string>
     */
    protected $cookies = array();

    /**
     * @var array<string, mixed>|object|null
     */
    protected $data = array();

    /**
     * @var array<string, \Zapheus\Contract\Http\Message\File[]>
     */
    protected $files = array();

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var array<string, mixed>
     */
    protected $queries = array();

    /**
     * @var array<string, string>
     */
    protected $server = array();

    /**
     * @var string
     */
    protected $target = '/';

    /**
     * @var \Zapheus\Contract\Http\Message\Uri
     */
    protected $uri;

    /**
     * Initializes the request instance.
     *
     * @param string                                             $method
     * @param string                                             $target
     * @param array<string, string>                              $server
     * @param array<string, string>                              $cookies
     * @param array<string, mixed>|object|null                   $data
     * @param array<string, \Zapheus\Contract\Http\Message\File[]> $files
     * @param array<string, mixed>                               $queries
     * @param array<string, mixed>                               $attributes
     * @param array<string, string[]>                            $headers
     * @param string                                             $version
     */
    public function __construct($method, $target, array $server = array(), array $cookies = array(), $data = null, array $files = array(), array $queries = array(), array $attributes = array(), array $headers = array(), $version = '1.1')
    {
        parent::__construct($headers, $version);

        $this->attributes = $attributes;

        $this->cookies = $cookies;

        $this->data = $data;

        $this->files = $files;

        $this->method = $method;

        $this->queries = $queries;

        $this->server = $server;

        $this->target = $target;
    }

    /**
     * Sets the URI instance.
     *
     * @param \Zapheus\Contract\Http\Message\Uri $uri
     *
     * @return self
     */
    public function setUri(UriContract $uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Sets the stream of the message.
     *
     * @param \Zapheus\Contract\Http\Message\Stream $stream
     *
     * @return self
     */
    public function setStream(StreamContract $stream)
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        if (! isset($this->attributes[$name]))
        {
            return $default;
        }

        return $this->attributes[$name];
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * @return array<string, mixed>
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Retrieve cookies.
     *
     * @return array<string, string>
     */
    public function getCookieParams()
    {
        return $this->cookies;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return array<string, mixed>|object|null
     */
    public function getParsedBody()
    {
        return $this->data;
    }

    /**
     * Retrieve query string arguments.
     *
     * @return array<string, mixed>
     */
    public function getQueryParams()
    {
        return $this->queries;
    }

    /**
     * Retrieves the message's request target.
     *
     * @return string
     */
    public function getRequestTarget()
    {
        return $this->target;
    }

    /**
     * Retrieve server parameters.
     *
     * @return array<string, string>
     */
    public function getServerParams()
    {
        return $this->server;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return array<string, \Zapheus\Contract\Http\Message\File[]>
     */
    public function getUploadedFiles()
    {
        return $this->files;
    }

    /**
     * Retrieves the URI instance.
     *
     * @return \Zapheus\Contract\Http\Message\Uri
     */
    public function getUri()
    {
        if ($this->uri === null)
        {
            $host = 'localhost';

            $scheme = 'http';

            $port = '80';

            if (isset($this->server['SERVER_NAME']))
            {
                $host = $this->server['SERVER_NAME'];
            }

            if (isset($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off')
            {
                $scheme = 'https';
            }

            if (isset($this->server['SERVER_PORT']))
            {
                $port = $this->server['SERVER_PORT'];
            }

            $this->uri = new Uri($scheme . '://' . $host . ':' . $port . $this->target);
        }

        return $this->uri;
    }
}
