<?php

namespace Zapheus\Http\Message;

/**
 * File Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface FileInterface
{
    /**
     * Returns the error associated with the uploaded file.
     *
     * @return integer
     */
    public function error();

    /**
     * Move the uploaded file to a new location.
     *
     * @param string $target
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function move($target);

    /**
     * Returns the filename sent by the client.
     *
     * @return string|null
     */
    public function name();

    /**
     * Returns the file size.
     *
     * @return integer|null
     */
    public function size();

    /**
     * Returns a stream representing the uploaded file.
     *
     * @return \Zapheus\Http\Message\StreamInterface
     * @throws \RuntimeException
     */
    public function stream();

    /**
     * Returns the media type sent by the client.
     *
     * @return string|null
     */
    public function type();
}
