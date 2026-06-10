<?php

namespace Zapheus;

use Zapheus\Http\Message\RequestInterface;
use Zapheus\Routing\Dispatcher;
use Zapheus\Routing\Router;

/**
 * Coordinator
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Coordinator extends Middlelayer
{
    /**
     * @var \Zapheus\Routing\RouterInterface
     */
    protected $original;

    /**
     * Initializes the application instance.
     *
     * @param \Zapheus\Application|null $application
     */
    public function __construct(Application $application = null)
    {
        parent::__construct($application);

        $this->original = new Router;
    }

    /**
     * Handles the request and returns a response.
     *
     * @param \Zapheus\Http\Message\RequestInterface $request
     *
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function handle(RequestInterface $request)
    {
        $interface = (string) Application::DISPATCHER;

        $dispatcher = new Dispatcher($this->original);

        $this->application->set($interface, $dispatcher);

        return $this->application->handle($request);
    }
}
