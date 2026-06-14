<?php

namespace Zapheus\Container;

use Zapheus\Contract\Container\Container as Contract;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer implements Contract
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function get($id)
    {
        if (! $this->has($id))
        {
            $text = 'Class "' . $id . '" does not exist.';

            throw new NotFoundException($text);
        }

        /** @var class-string $id */
        $reflect = new \ReflectionClass($id);

        $args = array();

        if ($const = $reflect->getConstructor())
        {
            $args = $this->resolve($const);
        }

        return $reflect->newInstanceArgs($args);
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
        return class_exists($id);
    }

    /**
     * Resolves constructor parameters via reflection.
     *
     * @param \ReflectionMethod $reflect
     *
     * @return array<integer, mixed>
     */
    protected function resolve(\ReflectionMethod $reflect)
    {
        $params = $reflect->getParameters();

        $items = array();

        foreach ($params as $key => $param)
        {
            $temp = new Parameter($param);

            if ($class = $temp->getClass())
            {
                $name = $class->getName();

                $items[$key] = $this->get($name);
            }
        }

        return $items;
    }
}
