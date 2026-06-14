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
     * @param array<string, array<string, string[]>> $files
     *
     * @return array<string, \Zapheus\Contract\Http\Message\File[]>
     */
    public function normalize(array $files)
    {
        $temp = $this->diverse($files);

        $items = array();

        foreach ($temp as $name => $file)
        {
            $rows = array();

            foreach ($file['name'] as $key => $value)
            {
                $rows[] = $this->create($file, $key);
            }

            $items[$name] = $rows;
        }

        return $items;
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
     * Creates the uploaded file instance from $_FILES.
     *
     * @param array<string, array<integer|string>> $file
     * @param integer                              $key
     *
     * @return \Zapheus\Contract\Http\Message\File
     */
    protected function create(array $file, $key)
    {
        /** @var string */
        $tmp = $file['tmp_name'][$key];
        $this->withFile($tmp);

        /** @var integer */
        $error = $file['error'][$key];
        $this->withError($error);

        /** @var string */
        $name = $file['name'][$key];
        $this->withClientFilename($name);

        return $this->make();
    }

    /**
     * Diverse the $_FILES into a consistent result.
     *
     * @param array<string, array<string, integer|string|string[]>> $files
     *
     * @return array<string, array<string, array<integer|string>>>
     */
    protected function diverse(array $files)
    {
        $rows = array();

        foreach ($files as $file => $items)
        {
            foreach ($items as $key => $item)
            {
                if (! is_array($item))
                {
                    $item = array($item);
                }

                $rows[$file][$key] = $item;
            }
        }

        return $rows;
    }
}
