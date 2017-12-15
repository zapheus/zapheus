<?php

namespace Slytherium\Container;

/**
 * Container Interface
 *
 * @package Slytherium
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param  string $id
     * @return mixed
     *
     * @throws \Rougin\Slytherin\Container\Exception\NotFoundException
     * @throws \Rougin\Slytherin\Container\Exception\ContainerException
     */
    public function get($id);

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id);
}
