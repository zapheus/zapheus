<?php

namespace Zapheus\Http\Message;

use Zapheus\Contract\Http\Message\File as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class File implements Contract
{
    /**
     * @var integer
     */
    protected $error;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var boolean
     */
    protected $moved = false;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer|null
     */
    protected $size;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @param string  $file
     * @param string  $name
     * @param integer $error
     */
    public function __construct($file, $name, $error = UPLOAD_ERR_OK)
    {
        $this->error = $error;

        $this->file = $file;

        $this->name = $name;

        $size = filesize($file);

        if ($size !== false)
        {
            $this->size = $size;
        }

        $type = mime_content_type($file);

        if ($type !== false)
        {
            $this->type = $type;
        }
    }

    /**
     * Returns the filepath of the uploaded file.
     *
     * @return string
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * Retrieve the filename sent by the client.
     *
     * @return string|null
     */
    public function getClientFilename()
    {
        return $this->name;
    }

    /**
     * Retrieve the media type sent by the client.
     *
     * @return string|null
     */
    public function getClientMediaType()
    {
        return $this->type;
    }

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * @return integer
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retrieve the file size.
     *
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     * @throws \RuntimeException
     */
    public function getStream()
    {
        if ($this->moved)
        {
            $text = 'Cannot retrieve stream after the file has been moved.';

            throw new \RuntimeException($text);
        }

        /** @var resource */
        $stream = fopen($this->file, 'r+');

        return new Stream($stream);
    }

    /**
     * Move the uploaded file to a new location.
     *
     * @param string $targetPath
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function moveTo($targetPath)
    {
        if (empty($targetPath))
        {
            $text = 'Target path must be a non-empty string.';

            throw new \InvalidArgumentException($text);
        }

        if ($this->moved)
        {
            $text = 'Cannot move the file; it has already been moved.';

            throw new \RuntimeException($text);
        }

        if ($this->error !== UPLOAD_ERR_OK)
        {
            $text = 'Cannot move the file; upload error code ' . $this->error . '.';

            throw new \RuntimeException($text);
        }

        $this->moved = true;

        rename($this->file, $targetPath);
    }
}
