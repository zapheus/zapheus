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
        if ($dotify)
        {
            return $this->flatten($this->data);
        }

        return $this->data;
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

        foreach ($keys as $index)
        {
            if (! is_array($items))
            {
                return $default;
            }

            if (! array_key_exists($index, $items))
            {
                return $default;
            }

            $items = $items[$index];
        }

        if ($items === null)
        {
            return $default;
        }

        if ($dotify && is_array($items))
        {
            return $this->flatten($items);
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
        $data = array();

        $items = array($path);

        if (substr($path, -4) !== '.php')
        {
            $dir = new \RecursiveDirectoryIterator($path);

            $item = new \RecursiveIteratorIterator($dir);

            $regex = '/^.+\.php$/i';

            $regex = new \RegexIterator($item, $regex, 1);

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
     * Sets the value to the specified key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function set($key, $value)
    {
        $keys = array_values(array_filter(explode('.', $key)));

        $count = count($keys);

        if ($count === 0)
        {
            return $this;
        }

        if ($count === 1)
        {
            $this->data[$keys[0]] = $value;

            return $this;
        }

        $nested = $value;

        for ($i = $count - 1; $i >= 1; $i--)
        {
            $nested = array($keys[$i] => $nested);
        }

        $root = $keys[0];

        if (isset($this->data[$root]) && is_array($this->data[$root]))
        {
            $this->data[$root] = array_replace_recursive($this->data[$root], $nested);
        }
        else
        {
            $this->data[$root] = $nested;
        }

        return $this;
    }

    /**
     * Converts a nested associative array into dot notation.
     *
     * @param mixed[] $data
     * @param string  $prefix
     *
     * @return array<string, mixed>
     */
    protected function flatten(array $data, $prefix = '')
    {
        $result = array();

        foreach ($data as $key => $value)
        {
            if (! is_string($key))
            {
                continue;
            }

            $path = $prefix === '' ? $key : $prefix . '.' . $key;

            if (is_array($value) && empty($value) === false)
            {
                $items = $this->flatten($value, $path);

                foreach ($items as $k => $v)
                {
                    $result[$k] = $v;
                }
            }
            else
            {
                $result[$path] = $value;
            }
        }

        return $result;
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

        $name = substr($name, 1);

        return basename(strtolower($name), '.php');
    }
}
