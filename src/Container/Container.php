<?php

namespace Zapheus\Container;

/**
 * Container
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Container implements WritableInterface
{
    /**
     * @var array
     */
    protected $objects = array();

    /**
     * Initializes the container instance.
     *
     * @param mixed[] $objects
     */
    public function __construct(array $objects = array())
    {
        $this->objects = $objects;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id
     *
     * @return mixed
     * @throws \Zapheus\Container\NotFoundException
     */
    public function get($id)
    {
        if ($this->has($id) === false)
        {
            throw new NotFoundException("Alias ($id) is not defined");
        }

        return $this->objects[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has($id)
    {
        return isset($this->objects[$id]);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     *
     * @return self
     */
    public function set($id, $concrete)
    {
        $this->objects[$id] = $concrete;

        return $this;
    }
}
