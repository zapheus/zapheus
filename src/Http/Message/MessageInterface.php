<?php

namespace Zapheus\Http\Message;

/**
 * Message Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface MessageInterface
{
    /**
     * Returns a message header value by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return array
     */
    public function header($name);

    /**
     * Returns all message header values.
     *
     * @return array
     */
    public function headers();

    /**
     * Returns the stream of the message.
     *
     * @return \Zapheus\Http\Message\StreamInterface
     */
    public function stream();

    /**
     * Returns the HTTP protocol version as a string.
     *
     * @return string
     */
    public function version();
}
