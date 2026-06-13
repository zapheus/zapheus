<?php

namespace Zapheus\Http\Message;

use Zapheus\Contract\Http\Message\Message as Contract;
use Zapheus\Contract\Http\Message\Stream as StreamContract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Message implements Contract
{
    /**
     * @var array<string, string[]>
     */
    protected $headers = array();

    /**
     * @var \Zapheus\Contract\Http\Message\Stream
     */
    protected $stream;

    /**
     * @var string
     */
    protected $version = '1.1';

    /**
     * @param array<string, string[]> $headers
     * @param string                  $version
     */
    public function __construct(array $headers = array(), $version = '1.1')
    {
        $this->headers = $headers;

        $this->version = $version;
    }

    /**
     * Gets the body of the message.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     */
    public function getBody()
    {
        if ($this->stream === null)
        {
            $stream = fopen('php://temp', 'r+');

            ! $stream && $stream = null;

            $this->stream = new Stream($stream);
        }

        return $this->stream;
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return string[]
     */
    public function getHeader($name)
    {
        $key = $this->getHeaderKey($name);

        $value = array();

        if ($key !== null)
        {
            $value = $this->headers[$key];
        }

        return $value;
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine($name)
    {
        $key = $this->getHeaderKey($name);

        $value = '';

        if ($key !== null)
        {
            $value = implode(',', $this->headers[$key]);
        }

        return $value;
    }

    /**
     * Retrieves all message header values.
     *
     * @return array<string, string[]>
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasHeader($name)
    {
        return $this->getHeaderKey($name) !== null;
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
     * Returns the actual header key matching the given case-insensitive name.
     *
     * @param string $name
     *
     * @return string|null
     */
    protected function getHeaderKey($name)
    {
        $name = strtolower($name);

        foreach (array_keys($this->headers) as $key)
        {
            if (strtolower($key) === $name)
            {
                return $key;
            }
        }

        return null;
    }
}
