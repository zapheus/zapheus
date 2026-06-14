<?php

namespace Zapheus\Http\Server;

use Zapheus\Container\ReflectionContainer;
use Zapheus\Contract\Container\Container;
use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Dispatcher as Contract;
use Zapheus\Contract\Http\Server\Handler;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements Contract
{
    /**
     * @var \Zapheus\Contract\Container\Container
     */
    protected $container;

    /**
     * @var array<integer, \Zapheus\Contract\Http\Server\Middleware>
     */
    protected $stack = array();

    /**
     * @param array<integer, \Zapheus\Contract\Http\Server\Middleware> $stack
     */
    public function __construct(array $stack = array())
    {
        foreach ($stack as $key => $item)
        {
            $this->stack[] = $item;
        }
    }

    /**
     * Dispatches the defined middleware stack.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function dispatch(Request $request)
    {
        $resolved = $this->resolve(0);

        return $resolved->handle($request);
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
     * Processes an incoming request and returns a response.
     *
     * @param \Zapheus\Contract\Http\Message\Request $request
     * @param \Zapheus\Contract\Http\Server\Handler  $handler
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function process(Request $request, Handler $handler)
    {
        $this->stack[] = new LastMiddleware($handler);

        $response = $this->dispatch($request);

        unset($this->stack[count($this->stack) - 1]);

        return $response;
    }

    /**
     * Sets the container for binding middleware dependencies.
     *
     * @param \Zapheus\Contract\Container\Container $container
     *
     * @return self
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Resolves the whole stack through its index.
     *
     * @param integer $index
     *
     * @return \Zapheus\Contract\Http\Server\Handler
     */
    protected function resolve($index)
    {
        if (! isset($this->stack[$index]))
        {
            return new ErrorHandler;
        }

        $next = $this->resolve($index + 1);

        $item = $this->transform($this->stack[$index]);

        return new NextHandler($item, $next);
    }

    /**
     * Transforms the specified input into a middleware.
     *
     * @param mixed $middleware
     *
     * @return \Zapheus\Contract\Http\Server\Middleware
     */
    protected function transform($middleware)
    {
        if (is_string($middleware))
        {
            if ($this->container === null)
            {
                $this->container = new ReflectionContainer;
            }

            return $this->container->get($middleware);
        }

        if (is_callable($middleware))
        {
            return new ClosureMiddleware($middleware);
        }

        return $middleware;
    }
}
