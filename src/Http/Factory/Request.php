<?php

namespace Zapheus\Http\Factory;

use Zapheus\Contract\Http\Message\File as FileContract;
use Zapheus\Http\Message\Request as Base;
use Zapheus\Http\Message\Uri as MessageUri;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Request extends Message
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
     * @var \Zapheus\Contract\Http\Message\Uri|null
     */
    protected $uri;

    /**
     * Creates the request instance.
     *
     * @return \Zapheus\Contract\Http\Message\Request
     */
    public function make()
    {
        $request = new Base($this->method, $this->target, $this->server, $this->cookies, $this->data, $this->files, $this->queries, $this->attributes, $this->headers, $this->version);

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
     * Sets the request instance and copies its properties.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return self
     */
    public function setRequest($request)
    {
        parent::setMessage($request);

        $this->attributes = $request->getAttributes();

        $this->cookies = $request->getCookieParams();

        $this->data = $request->getParsedBody();

        $this->files = $request->getUploadedFiles();

        $this->method = $request->getMethod();

        $this->queries = $request->getQueryParams();

        $this->server = $request->getServerParams();

        $this->target = $request->getRequestTarget();

        $this->uri = $request->getUri();

        return $this;
    }

    /**
     * Return an instance without the specified attribute.
     *
     * @param string $name
     *
     * @return self
     */
    public function withoutAttribute($name)
    {
        unset($this->attributes[$name]);

        return $this;
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return self
     */
    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Return an instance with the specified request attributes.
     *
     * @param array<string, mixed> $attributes
     *
     * @return self
     */
    public function withAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * @param array<string, string> $cookies
     *
     * @return self
     */
    public function withCookieParams(array $cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * Return an instance with the specified HTTP method.
     *
     * @param string $method
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public function withMethod($method)
    {
        if (empty($method))
        {
            $text = 'Method must be a non-empty string.';

            throw new \InvalidArgumentException($text);
        }

        $this->method = $method;

        return $this;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * @param array<string, mixed>|object|null $data
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public function withParsedBody($data)
    {
        if (! $this->isValidParsedBody($data))
        {
            $text = 'Parsed body must be null, an array, or an object.';

            throw new \InvalidArgumentException($text);
        }

        $this->data = $data;

        return $this;
    }

    /**
     * Return an instance with the specified query parameters.
     *
     * @param array<string, mixed> $queries
     *
     * @return self
     */
    public function withQueryParams(array $queries)
    {
        $this->queries = $queries;

        return $this;
    }

    /**
     * Return an instance with the specified request target.
     *
     * @param string $target
     *
     * @return self
     */
    public function withRequestTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Return an instance with the specified server parameters.
     *
     * @param array<string, string> $server
     *
     * @return self
     */
    public function withServerParams(array $server)
    {
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

        $this->uri = new MessageUri($link . ':' . $port);

        foreach ($server as $key => $value)
        {
            if (strpos($key, 'HTTP_') !== 0)
            {
                continue;
            }

            $string = strtolower(substr($key, 5));

            $name = str_replace('_', '-', $string);

            /** @var array<string, string> */
            $headerValue = is_array($value) ? $value : array($value);

            $this->headers[$name] = $headerValue;
        }

        return $this;
    }

    /**
     * Return an instance with the specified uploaded files.
     *
     * @param array<string, \Zapheus\Contract\Http\Message\File[]> $files
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public function withUploadedFiles(array $files)
    {
        foreach ($files as $items)
        {
            foreach ($items as $file)
            {
                $this->checkIfValidFile($file);
            }
        }

        $this->files = $files;

        return $this;
    }

    /**
     * Return an instance with the specified URI.
     *
     * @param \Zapheus\Http\Message\Uri $uri
     *
     * @return self
     */
    public function withUri(MessageUri $uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Validates the specified uploaded file.
     *
     * @param mixed $file
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkIfValidFile($file)
    {
        if ($file instanceof FileContract)
        {
            return;
        }

        $name = 'Zapheus\Contract\Http\Message\File';

        $text = 'Each file must be implemented in "' . $name . '".';

        throw new \InvalidArgumentException($text);
    }

    /**
     * Checks if the specified data is a valid parsed body type.
     *
     * @param mixed $data
     *
     * @return boolean
     */
    protected function isValidParsedBody($data)
    {
        return is_null($data) || is_array($data) || is_object($data);
    }
}
