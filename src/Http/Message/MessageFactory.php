<?php

namespace Zapheus\Http\Message;

use Zapheus\Contract\Http\Message\Message as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MessageFactory
{
    /**
     * @var array<string, mixed>
     */
    protected $headers = array();

    /**
     * @var \Zapheus\Contract\Http\Message\Stream|null
     */
    protected $stream;

    /**
     * @var string
     */
    protected $version = '1.1';

    /**
     * Sets the message instance and copies its properties.
     *
     * @param \Zapheus\Contract\Http\Message\Message $message
     *
     * @return self
     */
    public function setMessage(Contract $message)
    {
        $this->headers = $message->headers();

        $this->stream = $message->stream();

        $this->version = $message->version();

        return $this;
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
        $this->headers[$name] = is_array($value) ? $value : array($value);

        return $this;
    }

    /**
     * Sets the message header values.
     *
     * @param array<string, string[]> $headers
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
     * @return \Zapheus\Contract\Http\Message\Message
     */
    public function make()
    {
        $message = new Message($this->headers, $this->version);

        if ($this->stream)
        {
            $message->setStream($this->stream);
        }

        return $message;
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
        foreach ($server as $key => $value)
        {
            $string = strtolower(substr($key, 5));

            $key = str_replace('_', '-', $string);

            if (strpos($key, 'HTTP_') === 0)
            {
                $this->headers[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Sets the stream instance.
     *
     * @param \Zapheus\Http\Message\Stream $stream
     *
     * @return self
     */
    public function stream(Stream $stream)
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
