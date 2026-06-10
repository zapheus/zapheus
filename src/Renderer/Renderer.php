<?php

namespace Zapheus\Renderer;

/**
 * Renderer
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Renderer implements RendererInterface
{
    /**
     * @var string[]
     */
    protected $paths = array();

    /**
     * Initializes the renderer instance.
     *
     * @param array|string $paths
     */
    public function __construct($paths)
    {
        $this->paths = (array) $paths;
    }

    /**
     * Renders a file from a specified template.
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($template, array $data = array())
    {
        $name = str_replace('.', '/', $template);

        foreach ($this->paths as $key => $path)
        {
            $files = $this->files((string) $path);

            $item = $this->check($files, $path, $key, "$name.php");

            if ($item !== null)
            {
                return $this->extract($item, $data);
            }
        }

        $message = "Template file \"$name\" not found.";

        throw new \InvalidArgumentException($message);
    }

    /**
     * Checks if the specified file exists.
     *
     * @param array          $files
     * @param string         $path
     * @param integer|string $source
     * @param string         $template
     *
     * @return string|null
     */
    protected function check(array $files, $path, $source, $template)
    {
        $file = null;

        foreach ((array) $files as $key => $value)
        {
            $filepath = str_replace($path, $source, $value);

            $filepath = str_replace('\\', '/', $filepath);

            $filepath = preg_replace('/^\d\//i', '', $filepath);

            $lowercase = strtolower($filepath) === $template;

            if ($filepath === $template || $lowercase)
            {
                return $value;
            }
        }

        return $file;
    }

    /**
     * Extracts the contents of the specified file.
     *
     * @param string $filepath
     * @param array  $data
     *
     * @return string
     */
    protected function extract($filepath, array $data)
    {
        extract($data);

        ob_start();

        include $filepath;

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
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
        $directory = new \RecursiveDirectoryIterator($path);

        $iterator = new \RecursiveIteratorIterator($directory);

        $regex = new \RegexIterator($iterator, '/^.+\.php$/i', 1);

        return (array) array_keys(iterator_to_array($regex));
    }
}
