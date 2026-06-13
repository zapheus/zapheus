<?php

namespace Zapheus\Http\Factory;

use Zapheus\Contract\Http\Message\Message as Contract;
use Zapheus\Contract\Http\Message\Stream as StreamContract;
use Zapheus\Http\Message\Message as Base;
use Zapheus\Http\Message\Stream;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Message
{
    /**
     * @var array<string, string[]>
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
     * Creates the message instance.
     *
     * @return \Zapheus\Contract\Http\Message\Message
     */
    public function make()
    {
        $message = new Base($this->headers, $this->version);

        if ($this->stream)
        {
            $message->setStream($this->stream);
        }

        return $message;
    }

    /**
     * Sets the message instance and copies its properties.
     *
     * @param \Zapheus\Contract\Http\Message\Message $message
     *
     * @return self
     */
    public function setMessage(Contract $message)
    {
        $this->headers = $message->getHeaders();

        $this->stream = $message->getBody();

        $this->version = $message->getProtocolVersion();

        return $this;
    }

    /**
     * Return an instance with the provided header value.
     *
     * @param string                $name
     * @param string|string[]|mixed $value
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public function withAddedHeader($name, $value)
    {
        $this->checkIfValidName($name);

        /** @var string[] */
        $items = is_array($value) ? $value : array($value);

        if (isset($this->headers[$name]))
        {
            /** @var string[] */
            $existing = $this->headers[$name];

            $this->headers[$name] = array_merge($existing, $items);
        }
        else
        {
            $this->headers[$name] = $items;
        }

        return $this;
    }

    /**
     * Return an instance with the specified body.
     *
     * @param \Zapheus\Contract\Http\Message\Stream $body
     *
     * @return self
     */
    public function withBody(StreamContract $body)
    {
        $this->stream = $body;

        return $this;
    }

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * @param string                $name
     * @param string|string[]|mixed $value
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public function withHeader($name, $value)
    {
        $this->checkIfValidName($name);

        /** @var string[] */
        $items = is_array($value) ? $value : array($value);

        $this->headers[$name] = $items;

        return $this;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * @param string $version
     *
     * @return self
     */
    public function withProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Return an instance without the specified header.
     *
     * @param string $name
     *
     * @return self
     */
    public function withoutHeader($name)
    {
        unset($this->headers[$name]);

        return $this;
    }

    /**
     * Validates the specified header name.
     *
     * @param string $name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkIfValidName($name)
    {
        $pattern = '/^[a-zA-Z0-9!#$%&\'*+.^_`|~-]+$/';

        if (preg_match($pattern, $name))
        {
            return;
        }

        $text = 'Header name is not a valid RFC 7230 name.';

        throw new \InvalidArgumentException($text);
    }
}
