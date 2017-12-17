<?php

namespace Slytherium\Provider;

/**
 * Configuration Interface
 *
 * @package Slytherium
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ConfigurationInterface extends \ArrayAccess
{
    /**
     * Returns all the stored configurations.
     *
     * @param  boolean $dotify
     * @return array
     */
    public function all($dotify = false);

    /**
     * Returns the value from the specified key.
     *
     * @param  string     $key
     * @param  mixed|null $default
     * @param  boolean    $dotify
     * @return mixed
     */
    public function get($key, $default = null, $dotify = false);

    /**
     * Loads the configuration from a specified file or directory.
     *
     * @param  string  $path
     * @param  boolean $directory
     * @return self
     */
    public function load($path, $directory = false);

    /**
     * Sets the value to the specified key.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return self
     */
    public function set($key, $value);
}
