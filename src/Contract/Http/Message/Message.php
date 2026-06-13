<?php

namespace Zapheus\Contract\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Message
{
    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string
     */
    public function getProtocolVersion();

    /**
     * Retrieves all message header values.
     *
     * @return array<string, string[]>
     */
    public function getHeaders();

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasHeader($name);

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return string[]
     */
    public function getHeader($name);

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine($name);

    /**
     * Gets the body of the message.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     */
    public function getBody();
}
