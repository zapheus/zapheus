<?php

namespace Zapheus\Http\Server;

use Zapheus\Application;
use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Message\Response;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Routing\Route;
use Zapheus\Routing\Resolver;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResolverHandler implements Handler
{
    /**
     * @var \Zapheus\Contract\Container\Writable
     */
    protected $container;

    /**
     * @var \Zapheus\Contract\Routing\Route
     */
    protected $route;

    /**
     * @param \Zapheus\Contract\Container\Writable $container
     * @param \Zapheus\Contract\Routing\Route      $route
     */
    public function __construct(Writable $container, Route $route)
    {
        $this->container = $container;

        $this->route = $route;
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function handle(Request $request)
    {
        $class = Application::RESOLVER;

        if ($this->container->has($class))
        {
            /** @var \Zapheus\Contract\Routing\Resolver */
            $resolver = $this->container->get($class);

            $result = $resolver->resolve($this->route);

            return $this->response($result);
        }

        // Set the HTTP request to the container ---
        $class = Application::REQUEST;

        $this->container->set($class, $request);
        // -----------------------------------------

        $resolver = new Resolver($this->container);

        $result = $resolver->resolve($this->route);

        return $this->response($result);
    }

    /**
     * Converts the given result into a Response.
     *
     * @param mixed $result
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    protected function response($result)
    {
        $class = Application::RESPONSE;

        /** @var \Zapheus\Contract\Http\Message\Response */
        $response = $this->container->get($class);

        if ($result instanceof Response)
        {
            return $result;
        }

        if (is_string($result))
        {
            $response->getBody()->write($result);
        }

        return $response;
    }
}
