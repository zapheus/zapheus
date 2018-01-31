<?php

namespace Zapheus\Renderer;

/**
 * Renderer
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
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
     * @param  string $template
     * @param  array  $data
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function render($template, array $data = array())
    {
        $name = (string) str_replace('.', '/', $template);

        if (($file = $this->find($name . '.php')) === null) {
            $message = 'Template file "%s" not found.';

            $message = sprintf($message, $template);

            throw new \InvalidArgumentException($message);
        }

        return $this->extract($file, $data);
    }

    /**
     * Checks if the specified file exists.
     *
     * @param  array          $files
     * @param  string         $path
     * @param  string|integer $source
     * @param  string         $template
     * @return string|null
     */
    protected function check(array $files, $path, $source, $template)
    {
        foreach ((array) $files as $key => $value) {
            $filepath = str_replace($path, $source, $value);

            $filepath = str_replace('\\', '/', (string) $filepath);

            $filepath = preg_replace('/^\d\//i', '', $filepath);

            strtolower($filepath) === $template && $file = $value;
        }

        return isset($file) === true ? $file : null;
    }

    /**
     * Extracts the contents of the specified file.
     *
     * @param  string $filepath
     * @param  array  $data
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
     * @param  string $path
     * @return string[]
     */
    protected function files($path)
    {
        $directory = new \RecursiveDirectoryIterator($path);

        $iterator = new \RecursiveIteratorIterator($directory);

        $regex = new \RegexIterator($iterator, '/^.+\.php$/i', 1);

        return (array) array_keys(iterator_to_array($regex));
    }

    /**
     * Finds the specified template from the list of paths.
     *
     * @param  string $template
     * @return string|null
     */
    protected function find($template)
    {
        foreach ((array) $this->paths as $key => $path) {
            $files = (array) $this->files($path);

            $item = $this->check($files, $path, $key, $template);

            $item !== null && $file = $item;
        }

        return isset($file) ? $file : null;
    }
}
