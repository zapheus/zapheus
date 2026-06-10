<?php

namespace Zapheus\Http\Server;

use Zapheus\Container\ContainerInterface;
use Zapheus\Container\ReflectionContainer;
use Zapheus\Http\Message\RequestInterface;

/**
 * Middleware Dispatcher
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var \Zapheus\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $stack = array();

    /**
     * Initializes the dispatcher instance.
     *
     * @param \Zapheus\Http\Server\MiddlewareInterface[] $stack
     * @param \Zapheus\Container\ContainerInterface|null $container
     */
    public function __construct(array $stack = array(), ContainerInterface $container = null)
    {
        $this->container($container ?: new ReflectionContainer);

        foreach ((array) $stack as $key => $item)
        {
            $middleware = $this->transform($item);

            array_push($this->stack, $middleware);
        }
    }

    /**
     * Sets the container for binding middleware dependencies.
     *
     * @param \Zapheus\Container\ContainerInterface $container
     *
     * @return self
     */
    public function container(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Dispatches the defined middleware stack.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $resolved = $this->resolve(0);

        return $resolved->handle($request);
    }

    /**
     * Processes an incoming request and returns a response.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     * @param \Zapheus\Http\Server\HandlerInterface  $handler
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function process(RequestInterface $request, HandlerInterface $handler)
    {
        $this->stack[] = new LastMiddleware($handler);

        $response = $this->dispatch($request);

        unset($this->stack[count($this->stack) - 1]);

        return $response;
    }

    /**
     * Adds a new middleware to the stack.
     *
     * @param mixed $middleware
     *
     * @return self
     */
    public function pipe($middleware)
    {
        $this->stack[] = $this->transform($middleware);

        return $this;
    }

    /**
     * Transforms the specified input into a middleware.
     *
     * @param mixed $middleware
     *
     * @return \Zapheus\Http\Server\MiddlewareInterface
     */
    protected function transform($middleware)
    {
        if (is_string($middleware))
        {
            return $this->container->get($middleware);
        }

        if (is_callable($middleware))
        {
            return new ClosureMiddleware($middleware);
        }

        return $middleware;
    }

    /**
     * Resolves the whole stack through its index.
     *
     * @param integer $index
     *
     * @return \Zapheus\Http\Server\HandlerInterface
     */
    protected function resolve($index)
    {
        if (! isset($this->stack[$index]))
        {
            return new ErrorHandler;
        }

        $next = $this->resolve($index + 1);

        $item = $this->stack[(int) $index];

        return new NextHandler($item, $next);
    }
}
