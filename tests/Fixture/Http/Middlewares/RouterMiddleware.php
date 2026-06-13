<?php

namespace Zapheus\Fixture\Http\Middlewares;

use Zapheus\Application;
use Zapheus\Contract\Http\Message\Request;
use Zapheus\Contract\Http\Server\Handler;
use Zapheus\Contract\Http\Server\Middleware;
use Zapheus\Contract\Routing\Dispatcher;
use Zapheus\Http\Factory\Request as RequestFactory;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterMiddleware implements Middleware
{
    /**
     * @var \Zapheus\Contract\Routing\Dispatcher
     */
    protected $dispatcher;

    /**
     * @param \Zapheus\Contract\Routing\Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Zapheus\Contract\Http\Message\Request $request
     * @param \Zapheus\Contract\Http\Server\Handler  $handler
     *
     * @return \Zapheus\Contract\Http\Message\Response
     */
    public function process(Request $request, Handler $handler)
    {
        $attribute = Application::ROUTE_ATTRIBUTE;

        $path = $request->uri()->path();

        $method = $request->method();

        $route = $this->dispatcher->dispatch($method, $path);

        $factory = new RequestFactory;

        $factory = $factory->setRequest($request);

        $factory->attribute($attribute, $route);

        return $handler->handle($factory->make());
    }
}
