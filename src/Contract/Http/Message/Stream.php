<?php

namespace Zapheus\Contract\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Stream
{
    /**
     * Reads all data from the stream into a string.
     *
     * @return string
     */
    public function __toString();

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close();

    /**
     * Returns the remaining contents in a string.
     *
     * @return string
     */
    public function getContents();

    /**
     * Reads data from the stream.
     *
     * @param integer $length
     *
     * @return string
     */
    public function read($length);

    /**
     * Seeks to the beginning of the stream.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function rewind();

    /**
     * Writes data to the stream.
     *
     * @param string $string
     *
     * @return integer
     */
    public function write($string);
}
