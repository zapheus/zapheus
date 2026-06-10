<?php

namespace Zapheus\Container;

/**
 * Writable Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface WritableInterface extends ContainerInterface
{
    /**
     * Sets a new instance on the given entry to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     *
     * @return self
     * @throws \Zapheus\Container\ContainerException
     */
    public function set($id, $concrete);
}
