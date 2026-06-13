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
     * @var array<string, \Zapheus\Contract\Http\Message\File>
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
     * @param array<string, \Zapheus\Contract\Http\Message\File> $files
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
     * Returns an instance with the specified derived request attribute.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function attribute($name)
    {
        if (! isset($this->attributes[$name]))
        {
            return null;
        }

        return $this->attributes[$name];
    }

    /**
     * Returns an array of attributes derived from the request.
     *
     * @return array<string, mixed>
     */
    public function attributes()
    {
        return $this->attributes;

        // getAttributes
        // withAttribute
        // withoutAttribute
    }

    /**
     * Returns the specified cookie from request.
     *
     * @param string $name
     *
     * @return array<string, string>|string|null
     */
    public function cookie($name)
    {
        if (! isset($this->cookies[$name]))
        {
            return null;
        }

        return $this->cookies[$name];
    }

    /**
     * Returns the cookies from the request.
     *
     * @return array<string, string>
     */
    public function cookies()
    {
        return $this->cookies;

        // getCookieParams
        // withCookieParams
    }

    /**
     * Returns any parameters provided in the request body.
     *
     * @return array<string, mixed>|object|null
     */
    public function data()
    {
        return $this->data;

        // getParsedBody
        // withParsedBody
    }

    /**
     * Returns normalized file upload data.
     *
     * @return array<string, \Zapheus\Contract\Http\Message\File>
     */
    public function files()
    {
        return $this->files;

        // getUploadedFiles
        // withUploadedFiles
    }

    /**
     * Returns the HTTP method of the request.
     *
     * @return string
     */
    public function method()
    {
        return $this->method;

        // getMethod
        // withMethod
    }

    /**
     * Returns the query string arguments.
     *
     * @return array<string, mixed>
     */
    public function queries()
    {
        return $this->queries;

        // getQueryParams
        // withQueryParams
    }

    /**
     * Returns the specified query string argument.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function query($name)
    {
        if (! isset($this->queries[$name]))
        {
            return null;
        }

        return $this->queries[$name];
    }

    /**
     * Returns the server parameter/s.
     *
     * @param string|null $name
     *
     * @return array<string, string>|string|null
     */
    public function server($name = null)
    {
        $value = null;

        if ($name === null)
        {
            $value = $this->server;
        }

        $server = $this->server;

        if (isset($server[$name]))
        {
            $value = $server[$name];
        }

        return $value;

        // getServerParams
    }

    /**
     * Returns the message's request target.
     *
     * @return string
     */
    public function target()
    {
        return $this->target;

        // getRequestTarget
        // withRequestTarget
    }

    /**
     * Returns the URI instance.
     *
     * @return \Zapheus\Contract\Http\Message\Uri
     */
    public function uri()
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

            $this->uri = new \Zapheus\Http\Message\Uri($scheme . '://' . $host . ':' . $port . $this->target);
        }

        return $this->uri;

        // getUri
        // withUri
    }
}
