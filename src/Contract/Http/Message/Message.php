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
     * Returns a message header value by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return array<string, string>
     */
    public function header($name);

    /**
     * Returns all message header values.
     *
     * @return array<string, string[]>
     */
    public function headers();

    /**
     * Returns the stream of the message.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     */
    public function stream();

    /**
     * Returns the HTTP protocol version as a string.
     *
     * @return string
     */
    public function version();
}
