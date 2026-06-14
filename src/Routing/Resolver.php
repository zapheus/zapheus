<?php

namespace Zapheus\Routing;

use Zapheus\Container\Parameter;
use Zapheus\Container\ReflectionContainer;
use Zapheus\Contract\Container\Container;
use Zapheus\Contract\Routing\Resolver as Contract;
use Zapheus\Contract\Routing\Route as RouteContract;

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
    public function resolve(RouteContract $route)
    {
        $handler = $route->getHandler();

        if (is_string($handler))
        {
            $handler = explode('@', $handler);
        }

        $params = $route->getParams();

        if (is_array($handler))
        {
            /** @var string */
            $class = $handler[0];

            /** @var string */
            $method = $handler[1];

            $handler = array($this->instance($class), $method);

            $reflect = new \ReflectionMethod($class, $method);
        }
        else
        {
            $reflect = new \ReflectionFunction($handler);
        }

        $params = $this->parse($reflect, $params);

        $params = array_values($params);

        return call_user_func_array($handler, $params);
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

    /**
     * Resolves the specified parameters from a container.
     *
     * @param \ReflectionFunctionAbstract $reflect
     * @param array<string, string>       $params
     *
     * @return array<string, mixed>
     */
    protected function parse(\ReflectionFunctionAbstract $reflect, $params = array())
    {
        $args = array();

        foreach ($reflect->getParameters() as $key => $param)
        {
            $temp = new Parameter($param);

            $name = $temp->getName();

            if ($param->isDefaultValueAvailable())
            {
                $args[$name] = $param->getDefaultValue();
            }

            if ($params[$name])
            {
                $args[$name] = $params[$name];
            }
        }

        return $args;
    }
}
