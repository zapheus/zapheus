<?php

namespace Zapheus\Contract\Container;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Writable extends Container
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
