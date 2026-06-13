<?php

namespace Zapheus\Routing;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingProviderTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Container\Writable
     */
    protected $container;

    /**
     * @var \Zapheus\Contract\Routing\Router
     */
    protected $router;

    /**
     * @var \Zapheus\Routing\RoutingProvider
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_dispatcher_is_registered()
    {
        $expect = new Route('GET', '/', 'HailController@index');

        $dispatcher = Application::DISPATCHER;

        $container = $this->self->register($this->container);

        /** @var \Zapheus\Contract\Routing\Dispatcher */
        $dispatcher = $container->get($dispatcher);

        $actual = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_router_from_container_used()
    {
        $provider = new RoutingProvider;

        $this->container->set(Application::ROUTER, $this->router);

        $expect = new Route('GET', '/', 'HailController@index');

        $dispatcher = Application::DISPATCHER;

        $container = $provider->register($this->container);

        /** @var \Zapheus\Contract\Routing\Dispatcher */
        $dispatcher = $container->get($dispatcher);

        $actual = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $config    = new Configuration;
        $container = new Container;

        $route = new Route('GET', '/', 'HailController@index');

        $this->router = new Router(array($route));

        $container->set(get_class($this->router), $this->router);

        $config->set('app.router', 'Zapheus\Routing\Router');

        $this->container = $container->set(RoutingProvider::CONFIG, $config);

        $this->self = new RoutingProvider;
    }
}
