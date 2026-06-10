<?php

namespace Zapheus\Http\Message;

/**
 * Stream
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Stream implements StreamInterface
{
    /**
     * @var resource
     */
    protected $stream;

    /**
     * Initializes the stream instance.
     *
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * Reads all data from the stream into a string.
     *
     * @return string
     */
    public function __toString()
    {
        $this->rewind();

        return $this->contents();
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close()
    {
        fclose($this->stream);
    }

    /**
     * Returns the remaining contents in a string.
     *
     * @return string
     */
    public function contents()
    {
        return stream_get_contents($this->stream);
    }

    /**
     * Reads data from the stream.
     *
     * @param integer $length
     *
     * @return string
     */
    public function read($length)
    {
        return fread($this->stream, $length);
    }

    /**
     * Seeks to the beginning of the stream.
     *
     * @throws \RuntimeException
     */
    public function rewind()
    {
        rewind($this->stream);
    }

    /**
     * Writes data to the stream.
     *
     * @param string $string
     *
     * @return integer
     */
    public function write($string)
    {
        return fwrite($this->stream, $string);
    }
}
