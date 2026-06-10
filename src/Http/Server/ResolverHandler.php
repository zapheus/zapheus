<?php

namespace Zapheus\Http\Server;

use Zapheus\Application;
use Zapheus\Container\WritableInterface;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;
use Zapheus\Routing\Resolver;
use Zapheus\Routing\RouteInterface;

/**
 * Resolver Handler
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResolverHandler implements HandlerInterface
{
    /**
     * @var \Zapheus\Container\WritableInterface
     */
    protected $container;

    /**
     * @var \Zapheus\Routing\RouteInterface
     */
    protected $route;

    /**
     * Initializes the handler instance.
     *
     * @param \Zapheus\Container\WritableInterface $container
     * @param \Zapheus\Routing\RouteInterface      $route
     */
    public function __construct(WritableInterface $container, RouteInterface $route)
    {
        $this->container = $container;

        $this->route = $route;
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function handle(RequestInterface $request)
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
     * Converts the given result into a ResponseInterface.
     *
     * @param mixed $result
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    protected function response($result)
    {
        $response = $this->container->get(Application::RESPONSE);

        if ($result instanceof ResponseInterface)
        {
            return $result;
        }

        $response->stream()->write($result);

        return $response;
    }
}
