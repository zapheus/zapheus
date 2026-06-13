<?php

namespace Zapheus\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestFactory extends MessageFactory
{
    /**
     * @var array<string, mixed>
     */
    protected $attributes = array();

    /**
     * @var array<string, mixed>
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
     * @var \Zapheus\Contract\Http\Message\Uri|null
     */
    protected $uri;

    /**
     * Sets the request instance and copies its properties.
     *
     * @param \Zapheus\Http\Message\Request $request
     *
     * @return self
     */
    public function setRequest(Request $request)
    {
        parent::setMessage($request);

        $this->attributes = $request->attributes();

        $this->cookies = $request->cookies();

        $this->data = $request->data();

        $this->files = $request->files();

        $this->method = $request->method();

        $this->queries = $request->queries();

        $this->server = $request->server();

        $this->target = $request->target();

        $this->uri = $request->uri();

        return $this;
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
     * @param array<string, mixed> $attributes
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
     * @param array<string, string> $cookies
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
     * @param array<string, mixed> $data
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
     * @param \Zapheus\Contract\Http\Message\File[] $files
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
     * @return \Zapheus\Contract\Http\Message\Request
     */
    public function make()
    {
        $request = new Request($this->method, $this->target, $this->server, $this->cookies, $this->data, $this->files, $this->queries, $this->attributes, $this->headers, $this->version);

        if ($this->uri)
        {
            $request->setUri($this->uri);
        }

        if ($this->stream)
        {
            $request->setStream($this->stream);
        }

        return $request;
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
     * @param array<string, mixed> $queries
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
     * @param array<string, string> $server
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
     * @param \Zapheus\Http\Message\Uri $uri
     *
     * @return self
     */
    public function uri(Uri $uri)
    {
        $this->uri = $uri;

        return $this;
    }
}
