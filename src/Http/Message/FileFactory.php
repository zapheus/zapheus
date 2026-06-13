<?php

namespace Zapheus\Http\Message;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FileFactory
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
     * Sets the error associated with the uploaded file.
     *
     * @param integer $error
     *
     * @return self
     */
    public function error($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Sets the filename of the uploaded file.
     *
     * @param string $file
     *
     * @return self
     */
    public function file($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Creates the uploaded file instance.
     *
     * @return \Zapheus\Contract\Http\Message\File
     */
    public function make()
    {
        return new File($this->file, $this->name, $this->error);
    }

    /**
     * Sets the name of the uploaded file.
     *
     * @param string $name
     *
     * @return self
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Parses the $_FILES into multiple \File instances.
     *
     * @param array<string, mixed> $uploaded
     * @param array<string, mixed> $files
     *
     * @return array<string, \Zapheus\Contract\Http\Message\File>
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

                array_push($items, $this->make());
            }

            $files[$name] = $items;
        }

        return $files;
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
