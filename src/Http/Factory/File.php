<?php

namespace Zapheus\Http\Factory;

use Zapheus\Http\Message\File as Base;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class File
{
    /**
     * @var integer
     */
    protected $error = UPLOAD_ERR_OK;

    /**
     * @var string
     */
    protected $file = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * Creates the uploaded file instance.
     *
     * @return \Zapheus\Contract\Http\Message\File
     */
    public function make()
    {
        return new Base($this->file, $this->name, $this->error);
    }

    /**
     * Parses the $_FILES into multiple \File instances.
     *
     * @param array<string, mixed> $uploaded
     * @param array<string, mixed> $files
     *
     * @return array<string, \Zapheus\Contract\Http\Message\File[]>
     */
    public function normalize(array $uploaded, $files = array())
    {
        foreach ($this->diverse($uploaded) as $name => $file)
        {
            $items = array();

            foreach ($file['name'] as $key => $value)
            {
                $this->file = $file['tmp_name'][$key];

                $this->name = $file['name'][$key];

                $this->error = $file['error'][$key];

                $items[] = $this->make();
            }

            $files[$name] = $items;
        }

        return $files;
    }

    /**
     * Return an instance with the specified client filename.
     *
     * @param string $name
     *
     * @return self
     */
    public function withClientFilename($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return an instance with the specified upload error.
     *
     * @param integer $error
     *
     * @return self
     */
    public function withError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Return an instance with the specified file path.
     *
     * @param string $file
     *
     * @return self
     */
    public function withFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Diverse the $_FILES into a consistent result.
     *
     * @param array<string, mixed> $uploaded
     *
     * @return array<string, mixed[]>
     */
    protected function diverse(array $uploaded)
    {
        $result = array();

        foreach ($uploaded as $file => $item)
        {
            foreach ($item as $key => $value)
            {
                $diversed = is_array($value) ? $value : array($value);

                $result[$file][$key] = $diversed;
            }
        }

        return $result;
    }
}
