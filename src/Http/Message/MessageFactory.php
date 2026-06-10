<?php

namespace Zapheus\Http\Message;

/**
 * Message Factory
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageFactory
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
     * @param \Zapheus\Http\Message\MessageInterface|null $message
     */
    public function __construct(MessageInterface $message = null)
    {
        if ($message === null)
        {
            return;
        }

        $this->headers = $message->headers();

        $this->stream = $message->stream();

        $this->version = $message->version();
    }

    /**
     * Sets a message header value.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return self
     */
    public function header($name, $value)
    {
        $this->headers[$name] = (array) $value;

        return $this;
    }

    /**
     * Sets the message header values.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return self
     */
    public function headers(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Creates the message instance.
     *
     * @return \Zapheus\Http\Message\MessageInterface
     */
    public function make()
    {
        return new Message($this->headers, $this->stream, $this->version);
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
        foreach ((array) $server as $key => $value)
        {
            $string = strtolower(substr($key, 5));

            $key = str_replace('_', '-', $string);

            if (strpos($key, 'HTTP_') === 0)
            {
                $this->headers[$key] = $value;
            }
        }
    }

    /**
     * Sets the stream instance.
     *
     * @param \Zapheus\Http\Message\StreamInterface $stream
     *
     * @return self
     */
    public function stream(StreamInterface $stream)
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * Sets the protocol version.
     *
     * @param string $version
     *
     * @return self
     */
    public function version($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Writes data directly to the stream.
     *
     * @param string $output
     *
     * @return self
     */
    public function write($output)
    {
        $resource = fopen('php://temp', 'r+');

        ! $resource && $resource = null;

        $stream = new Stream($resource);

        $stream->write((string) $output);

        $this->stream = $stream;

        return $this;
    }
}
