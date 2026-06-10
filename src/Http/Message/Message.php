<?php

namespace Zapheus\Http\Message;

/**
 * Message
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Message implements MessageInterface
{
    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @var \Zapheus\Http\Message\StreamInterface
     */
    protected $stream;

    /**
     * @var string
     */
    protected $version = '1.1';

    /**
     * Initializes the message instance.
     *
     * @param array                                      $headers
     * @param \Zapheus\Http\Message\StreamInterface|null $stream
     * @param string                                     $version
     */
    public function __construct(array $headers = array(), StreamInterface $stream = null, $version = '1.1')
    {
        $this->headers = (array) $headers;

        if ($stream === null)
        {
            $stream = fopen('php://temp', 'r+');

            ! $stream && $stream = null;

            $stream = new Stream($stream);
        }

        $this->stream = $stream;

        $this->version = $version;
    }

    /**
     * Returns a message header value by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return array
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
     * @return array
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
     * @return \Zapheus\Http\Message\StreamInterface
     */
    public function stream()
    {
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
