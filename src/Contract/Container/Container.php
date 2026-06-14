<?php

namespace Zapheus\Contract\Container;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Container
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id
     *
     * @return mixed
     * @throws \Zapheus\Container\NotFoundException
     * @throws \Zapheus\Container\ContainerException
     */
    public function get($id);

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has($id);
}
