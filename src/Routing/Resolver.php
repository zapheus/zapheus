<?php

namespace Zapheus\Routing;

use Zapheus\Container\ContainerInterface;

/**
 * Resolver
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Resolver implements ResolverInterface
{
    /**
     * @var \Zapheus\Container\ContainerInterface
     */
    protected $container;

    /**
     * Initializes the resolver instance.
     *
     * @param \Zapheus\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Resolves the specified route instance.
     *
     * @param \Zapheus\Routing\RouteInterface $route
     *
     * @return mixed
     */
    public function resolve(RouteInterface $route)
    {
        if (is_string($handler = $route->handler()))
        {
            $handler = explode('@', $handler);
        }

        $parameters = (array) $route->parameters();

        list($handler, $reflection) = $this->reflection($handler);

        $parameters = $this->arguments($reflection, $parameters);

        return call_user_func_array($handler, array_values($parameters));
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param \ReflectionFunctionAbstract $reflection
     * @param array                       $parameters
     *
     * @return array
     */
    protected function arguments(\ReflectionFunctionAbstract $reflection, $parameters = array())
    {
        $arguments = array();

        foreach ($reflection->getParameters() as $key => $parameter)
        {
            $name = $parameter->getName();

            if ($class = $parameter->getClass())
            {
                $name = $class->getName();
            }

            if (isset($parameters[$name]))
            {
                if ($parameter->isDefaultValueAvailable())
                {
                    $arguments[$name] = $parameter->getDefaultValue();
                }

                if ($parameters[$name])
                {
                    $arguments[$name] = $parameters[$name];
                }

                continue;
            }

            $arguments[$name] = $this->instance($name);
        }

        return $arguments;
    }

    /**
     * Returns the instance of the identifier from the container.
     *
     * @param string $class
     *
     * @return mixed
     */
    protected function instance($class)
    {
        $reflection = new \ReflectionClass($class);

        $arguments = array();

        $constructor = $reflection->getConstructor();

        if ($this->container->has($class))
        {
            return $this->container->get($class);
        }

        if ($constructor !== null)
        {
            $arguments = $this->arguments($constructor);
        }

        return $reflection->newInstanceArgs(array_values($arguments));
    }

    /**
     * Returns a ReflectionFunctionAbstract instance.
     *
     * @param array|callable $handler
     *
     * @return \ReflectionFunctionAbstract
     */
    protected function reflection($handler)
    {
        if (! is_array($handler))
        {
            $instance = new \ReflectionFunction($handler);

            return array($handler, $instance);
        }

        list($class, $method) = (array) $handler;

        $instance = new \ReflectionMethod($class, $method);

        $handler = array($this->instance($class), $method);

        return array((array) $handler, $instance);
    }
}
