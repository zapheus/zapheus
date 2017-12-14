<?php

namespace Slytherium\Http\Message;

/**
 * Message Interface
 *
 * @package Slytherium
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface MessageInterface
{
    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string
     */
    public function getProtocolVersion();

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * @param  string $version
     * @return static
     */
    public function withProtocolVersion($version);

    /**
     * Retrieves all message header values.
     *
     * @return string[][]
     */
    public function getHeaders();

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param  string $name
     * @return boolean
     */
    public function hasHeader($name);

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * @param  string $name
     * @return string[]
     */
    public function getHeader($name);

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * @param  string $name
     * @return string
     */
    public function getHeaderLine($name);

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * @param  string          $name
     * @param  string|string[] $value
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withHeader($name, $value);

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * @param  string          $name
     * @param  string|string[] $value
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withAddedHeader($name, $value);

    /**
     * Return an instance without the specified header.
     *
     * @param  string $name
     * @return static
     */
    public function withoutHeader($name);

    /**
     * Gets the body of the message.
     *
     * @return \Slytherium\Http\Message\StreamInterface
     */
    public function getBody();

    /**
     * Return an instance with the specified message body.
     *
     * @param  \Slytherium\Http\Message\StreamInterface $body
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withBody(StreamInterface $body);
}
