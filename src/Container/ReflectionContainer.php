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
     * @throws \Zapheus\Container\NotFoundException
     */
    public function get($id)
    {
        if ($this->has($id) === false)
        {
            throw new NotFoundException("Class ($id) does not exists");
        }

        $reflection = new \ReflectionClass($id);

        if ($constructor = $reflection->getConstructor())
        {
            $arguments = $this->arguments($constructor);

            return $reflection->newInstanceArgs($arguments);
        }

        return new $id;
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
     * Resolves the specified parameters from a container.
     *
     * @param \ReflectionFunctionAbstract $reflection
     *
     * @return array
     */
    protected function arguments(\ReflectionFunctionAbstract $reflection)
    {
        $arguments = array();

        foreach ($reflection->getParameters() as $key => $parameter)
        {
            $name = $parameter->getName();

            if ($class = $parameter->getClass())
            {
                $name = $class->getName();
            }

            try
            {
                $arguments[$key] = $parameter->getDefaultValue();
            }
            catch (\ReflectionException $exception)
            {
                $arguments[$key] = $this->get((string) $name);
            }
        }

        return $arguments;
    }
}
