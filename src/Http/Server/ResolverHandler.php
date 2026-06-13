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
     * Initializes the handler instance.
     *
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
        if ($this->container->has(Application::RESOLVER) === true)
        {
            $resolver = $this->container->get(Application::RESOLVER);

            return $this->response($resolver->resolve($this->route));
        }

        $this->container->set(Application::REQUEST, $request);

        $resolver = new Resolver($this->container);

        return $this->response($resolver->resolve($this->route));
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
        $response = $this->container->get(Application::RESPONSE);

        if ($result instanceof Response)
        {
            return $result;
        }

        $response->stream()->write($result);

        return $response;
    }
}
