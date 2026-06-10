<?php

namespace Zapheus\Container;

/**
 * Reflection Container
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer implements ContainerInterface
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
            throw new NotFoundException('Class "' . $id . '" does not exist.');
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
            if ($class = $this->getParam($param))
            {
                $name = $class->getName();

                $items[$key] = $this->get($name);
            }
        }

        return $items;
    }

    /**
     * @codeCoverageIgnore
     *
     * Returns the ReflectionClass for a parameter,
     * compatible with PHP 5.3 through 8.x.
     *
     * @param \ReflectionParameter $param
     *
     * @return \ReflectionClass<object>|null
     */
    protected function getParam(\ReflectionParameter $param)
    {
        $php8 = version_compare(PHP_VERSION, '8.0.0', '>=');

        if (! $php8)
        {
            $fn = array($param, 'getClass');

            return call_user_func($fn);
        }

        $fn = array($param, 'getType');

        $type = call_user_func($fn);

        $builtIn = true;

        if ($type)
        {
            /** @var callable */
            $fn = array($type, 'isBuiltin');

            /** @var boolean */
            $builtIn = call_user_func($fn);
        }

        if ($builtIn)
        {
            return null;
        }

        /** @var callable */
        $class = array($type, 'getName');

        /** @var class-string */
        $fn = call_user_func($class);

        return new \ReflectionClass($fn);
    }
}
