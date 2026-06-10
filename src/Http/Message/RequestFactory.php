<?php

namespace Zapheus\Http\Message;

/**
 * Request Factory
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestFactory extends MessageFactory
{
    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * @var array
     */
    protected $cookies = array();

    /**
     * @var array|object|null
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var array
     */
    protected $queries = array();

    /**
     * @var array
     */
    protected $server = array();

    /**
     * @var string
     */
    protected $target = '/';

    /**
     * @var \Zapheus\Http\Message\UriInterface
     */
    protected $uri;

    /**
     * Initializes the request instance.
     *
     * @param \Zapheus\Http\Message\RequestInterface|null $request
     */
    public function __construct(RequestInterface $request = null)
    {
        parent::__construct($request);

        if ($request === null)
        {
            return;
        }

        $this->attributes = $request->attributes();

        $this->cookies = $request->cookies();

        $this->data = $request->data();

        $this->files = $request->files();

        $this->method = $request->method();

        $this->queries = $request->queries();

        $this->server = $request->server();

        $this->target = $request->target();

        $this->uri = $request->uri();
    }

    /**
     * Sets a single attribute value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function attribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Sets the attributes value.
     *
     * @param array $attributes
     *
     * @return self
     */
    public function attributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Sets a single cookie parameter.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function cookie($key, $value)
    {
        $this->cookies[$key] = $value;

        return $this;
    }

    /**
     * Sets the cookies parameter ($_COOKIE).
     *
     * @param array $cookies
     *
     * @return self
     */
    public function cookies(array $cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * Sets the data parameter ($_POST).
     *
     * @param array $data
     *
     * @return self
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Sets the files parameter.
     *
     * @param \Zapheus\Http\Message\FileInterface[] $files
     *
     * @return self
     */
    public function files(array $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Creates the request instance.
     *
     * @return \Zapheus\Http\Message\RequestInterface
     */
    public function make()
    {
        return new Request($this->method, $this->target, $this->server, $this->cookies, $this->data, $this->files, $this->queries, $this->attributes, $this->uri, $this->headers, $this->stream, $this->version);
    }

    /**
     * Sets the HTTP method.
     *
     * @param string $method
     *
     * @return self
     */
    public function method($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Sets the query parameters ($_GET).
     *
     * @param array $queries
     *
     * @return self
     */
    public function queries(array $queries)
    {
        $this->queries = $queries;

        return $this;
    }

    /**
     * Sets a single query parameter.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return self
     */
    public function query($name, $value)
    {
        $this->queries[$name] = $value;

        return $this;
    }

    /**
     * Sets the server parameters ($_SERVER).
     *
     * @param array $server
     *
     * @return self
     */
    public function server(array $server)
    {
        parent::server($server);

        $this->server = $server;

        $this->method = $server['REQUEST_METHOD'];

        $this->target = $server['REQUEST_URI'];

        $http = 'https://';

        if (! isset($server['HTTPS']) || $server['HTTPS'] === 'off')
        {
            $http = 'http://';
        }

        $link = $http . $server['SERVER_NAME'];

        $port = $server['SERVER_PORT'] . $this->target;

        $this->uri = new Uri($link . ':' . $port);

        return $this;
    }

    /**
     * Sets the request target.
     *
     * @param string $target
     *
     * @return self
     */
    public function target($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Sets the URI instance.
     *
     * @param \Zapheus\Http\Message\UriInterface $uri
     *
     * @return self
     */
    public function uri(UriInterface $uri)
    {
        $this->uri = $uri;

        return $this;
    }
}
