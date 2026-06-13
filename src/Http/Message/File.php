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
     * @var string
     */
    protected $name;

    /**
     * @var false|integer|null
     */
    protected $size;

    /**
     * @var false|string
     */
    protected $type;

    /**
     * Initializes the uploaded file instance.
     *
     * @param string  $file
     * @param string  $name
     * @param integer $error
     */
    public function __construct($file, $name, $error = UPLOAD_ERR_OK)
    {
        $this->error = $error;

        $this->file = $file;

        $this->name = $name;

        $this->size = filesize($file);

        $this->type = mime_content_type($file);
    }

    /**
     * Returns the error associated with the uploaded file.
     *
     * @return integer
     */
    public function error()
    {
        return $this->error;
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
     * Move the uploaded file to a new location.
     *
     * @param string $target
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function move($target)
    {
        rename($this->file, $target);
    }

    /**
     * Returns the filename sent by the client.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns the file size.
     *
     * @return integer|null
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * Returns a stream representing the uploaded file.
     *
     * @return \Zapheus\Contract\Http\Message\Stream
     * @throws \RuntimeException
     */
    public function stream()
    {
        $stream = fopen($this->file, 'r');

        $stream || $stream = null;

        return new Stream($stream);
    }

    /**
     * Returns the media type sent by the client.
     *
     * @return string|null
     */
    public function type()
    {
        return $this->type;
    }
}
