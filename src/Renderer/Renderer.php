<?php

namespace Zapheus\Renderer;

use Zapheus\Contract\Renderer\Renderer as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Renderer implements Contract
{
    /**
     * @var string[]
     */
    protected $paths = array();

    /**
     * @param string|string[] $paths
     */
    public function __construct($paths)
    {
        if (is_string($paths))
        {
            $paths = array($paths);
        }

        $this->paths = $paths;
    }

    /**
     * Renders a file from a specified template.
     *
     * @param string               $plate
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($plate, array $data = array())
    {
        $name = str_replace('.', '/', $plate);

        foreach ($this->paths as $path)
        {
            $files = $this->files($path);

            $temp = $name . '.php';

            $item = $this->check($files, $path, $temp);

            if ($item !== null)
            {
                return $this->extract($item, $data);
            }
        }

        $text = "Template file \"$name\" not found.";

        throw new \InvalidArgumentException($text);
    }

    /**
     * Checks if the specified file exists.
     *
     * @param string[] $files
     * @param string   $path
     * @param string   $plate
     *
     * @return string|null
     */
    protected function check($files, $path, $plate)
    {
        foreach ($files as $value)
        {
            $temp = str_replace($path, '', $value);

            $temp = str_replace('\\', '/', $temp);

            $temp = ltrim($temp, '/');

            $lower = strtolower($temp) === $plate;

            if ($temp === $plate || $lower)
            {
                return $value;
            }
        }

        return null;
    }

    /**
     * Extracts the contents of the specified file.
     *
     * @param string               $path
     * @param array<string, mixed> $data
     *
     * @return string
     */
    protected function extract($path, array $data)
    {
        extract($data);

        ob_start();

        include $path;

        /** @var string */
        $result = ob_get_contents();

        ob_end_clean();

        return $result;
    }

    /**
     * Returns an array of filepaths from a specified directory.
     *
     * @param string $path
     *
     * @return string[]
     */
    protected function files($path)
    {
        $dir = new \RecursiveDirectoryIterator($path);

        $item = new \RecursiveIteratorIterator($dir);

        $regex = '/^.+\.php$/i';

        $regex = new \RegexIterator($item, $regex, 1);

        return array_keys(iterator_to_array($regex));
    }
}
