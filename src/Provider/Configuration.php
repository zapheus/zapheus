<?php

namespace Zapheus\Provider;

use Zapheus\Contract\Provider\Configuration as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Configuration implements Contract
{
    /**
     * @var array<string, mixed>
     */
    protected $data = array();

    /**
     * Initializes the configuration instance.
     *
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Returns all the stored configurations.
     *
     * @param boolean $dotify
     *
     * @return array<string, mixed>
     */
    public function all($dotify = false)
    {
        return $dotify ? $this->dotify($this->data) : $this->data;
    }

    /**
     * Returns the value from the specified key.
     *
     * @param string     $key
     * @param mixed|null $default
     * @param boolean    $dotify
     *
     * @return mixed
     */
    public function get($key, $default = null, $dotify = false)
    {
        $items = $this->data;

        $keys = array_filter(explode('.', $key));

        $length = count($keys);

        for ($i = 0; $i < $length; $i++)
        {
            $index = $keys[$i];

            $items = &$items[$index];
        }

        if ($items === null)
        {
            $items = $default;
        }

        if ($dotify)
        {
            return $this->dotify($items);
        }

        return $items;
    }

    /**
     * Loads an array of values from a specified file or directory.
     *
     * @param string $path
     *
     * @return void
     */
    public function load($path)
    {
        $data  = array();
        $items = array($path);

        if (substr($path, -4) !== '.php')
        {
            $directory = new \RecursiveDirectoryIterator($path);

            $iterator = new \RecursiveIteratorIterator($directory);

            $regex = new \RegexIterator($iterator, '/^.+\.php$/i', 1);

            $items = array_keys(iterator_to_array($regex));
        }

        foreach ($items as $item)
        {
            $name = $this->rename($item, $path);

            $data = require $item;

            $this->set($name, $data);
        }
    }

    /**
     * Converts the data into dot notation values.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $result
     * @param string               $key
     *
     * @return array<string, mixed>
     */
    protected function dotify(array $data, $result = array(), $key = '')
    {
        foreach ($data as $name => $value)
        {
            if (is_array($value) && empty($value) === false)
            {
                $text = $key . $name . '.';

                $item = $this->dotify($value, $result, $text);

                $result = array_merge($result, $item);

                continue;
            }

            $result[$key . $name] = $value;
        }

        return $result;
    }

    /**
     * Sets the value to the specified key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function set($key, $value)
    {
        $keys = array_filter(explode('.', $key));

        $this->save($keys, $this->data, $value);

        return $this;
    }

    /**
     * Renames the item into a dot notation one.
     *
     * @param string $item
     * @param string $path
     *
     * @return string
     */
    protected function rename($item, $path)
    {
        $name = str_replace($path, '', $item);

        $name = str_replace(array('\\', '/'), '.', $name);

        $regex = preg_replace('/^./i', '', $name);

        return basename(strtolower($regex), '.php');
    }

    /**
     * Saves the specified key in the list of data.
     *
     * @param string[]             &$keys
     * @param array<string, mixed> &$data
     * @param mixed                $value
     *
     * @return mixed
     */
    protected function save(array &$keys, &$data, $value)
    {
        $key = array_shift($keys);

        if (! isset($data[$key]))
        {
            $data[$key] = array();
        }

        if (empty($keys))
        {
            return $data[$key] = $value;
        }

        return $this->save($keys, $data[$key], $value);
    }
}
