<?php

namespace Zapheus\Http\Message;

/**
 * Mutator Interface
 *
 * @package Zapheus
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface MutatorInterface
{
    /**
     * Sets a value to a specified property.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return self
     */
    public function set($name, $value);
}
