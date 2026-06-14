<?php

namespace Zapheus\Container;

use Zapheus\Contract\Container\Writable;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Container implements Writable
{
    /**
     * @var array<mixed>
     */
    protected $items = array();

    /**
     * @param mixed[] $items
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
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
        if (! $this->has($id))
        {
            $text = 'Alias (' . $id . ') is not defined';

            throw new NotFoundException($text);
        }

        return $this->items[$id];
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
        return isset($this->items[$id]);
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
        $this->items[$id] = $concrete;

        return $this;
    }
}
