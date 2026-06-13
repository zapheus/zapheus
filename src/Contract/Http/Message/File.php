<?php

namespace Zapheus\Contract\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface File
{
    /**
     * Retrieve a stream representing the uploaded file.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     * @throws \RuntimeException
     */
    public function getStream();

    /**
     * Move the uploaded file to a new location.
     *
     * @param string $targetPath
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function moveTo($targetPath);

    /**
     * Retrieve the file size.
     *
     * @return integer|null
     */
    public function getSize();

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * @return integer
     */
    public function getError();

    /**
     * Retrieve the filename sent by the client.
     *
     * @return string|null
     */
    public function getClientFilename();

    /**
     * Retrieve the media type sent by the client.
     *
     * @return string|null
     */
    public function getClientMediaType();
}
