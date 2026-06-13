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
     * Initializes the message instance.
     *
     * @param array<string, string[]> $headers
     * @param string                  $version
     */
    public function __construct(array $headers = array(), $version = '1.1')
    {
        $this->headers = $headers;

        $this->version = $version;
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
     * Returns a message header value by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return string[]
     */
    public function header($name)
    {
        if (! isset($this->headers[$name]))
        {
            return array();
        }

        return $this->headers[$name];

        // getHeader
    }

    /**
     * Returns all message header values.
     *
     * @return array<string, string[]>
     */
    public function headers()
    {
        return $this->headers;

        // getHeaders
        // hasHeader
        // getHeaderLine
        // withHeader
        // withAddedHeader
        // withoutHeader
    }

    /**
     * Returns the stream of the message.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     */
    public function stream()
    {
        if ($this->stream === null)
        {
            $stream = fopen('php://temp', 'r+');

            ! $stream && $stream = null;

            $this->stream = new Stream($stream);
        }

        return $this->stream;

        // getBody
        // withBody
    }

    /**
     * Returns the HTTP protocol version as a string.
     *
     * @return string
     */
    public function version()
    {
        return $this->version;

        // getProtocolVersion
        // withProtocolVersion
    }
}
