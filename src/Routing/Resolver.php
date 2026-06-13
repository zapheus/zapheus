<?php

namespace Zapheus\Routing;

use Zapheus\Container\ReflectionContainer;
use Zapheus\Contract\Container\Container;
use Zapheus\Contract\Routing\Resolver as Contract;
use Zapheus\Contract\Routing\Route;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Resolver implements Contract
{
    /**
     * @var \Zapheus\Contract\Container\Container
     */
    protected $container;

    /**
     * @var \Zapheus\Container\ReflectionContainer
     */
    protected $reflect;

    /**
     * Initializes the resolver instance.
     *
     * @param \Zapheus\Contract\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->reflect = new ReflectionContainer;
    }

    /**
     * Resolves the specified route instance.
     *
     * @param \Zapheus\Contract\Routing\Route $route
     *
     * @return mixed
     */
    public function resolve(Route $route)
    {
        if (is_string($handler = $route->handler()))
        {
            $handler = explode('@', $handler);
        }

        $parameters = $route->parameters();

        if (! is_array($handler))
        {
            $reflection = new \ReflectionFunction($handler);
        }
        else
        {
            $class = $handler[0];
            $method = $handler[1];

            $handler = array($this->instance($class), $method);

            $reflection = new \ReflectionMethod($class, $method);
        }

        $parameters = $this->arguments($reflection, $parameters);

        return call_user_func_array($handler, array_values($parameters));
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param \ReflectionFunctionAbstract $reflection
     * @param array<string, string>       $parameters
     *
     * @return array<string, mixed>
     */
    protected function arguments(\ReflectionFunctionAbstract $reflection, $parameters = array())
    {
        $args = array();

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
                    $args[$name] = $parameter->getDefaultValue();
                }

                if ($parameters[$name])
                {
                    $args[$name] = $parameters[$name];
                }

                continue;
            }

            $args[$name] = $this->instance($name);
        }

        return $args;
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
        if ($this->container->has($class))
        {
            return $this->container->get($class);
        }

        return $this->reflect->get($class);
    }
}
