<?php

namespace Zapheus\Routing;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * Routing Provider Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingProviderTest extends Testcase
{
    /**
     * @var \Zapheus\Container\WritableInterface
     */
    protected $container;

    /**
     * @var \Zapheus\Provider\ProviderInterface
     */
    protected $provider;

    /**
     * @var \Zapheus\Routing\RouterInterface
     */
    protected $router;

    /**
     * Sets up the provider instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        list($config, $container) = array(new Configuration, new Container);

        $route = new Route('GET', '/', 'HailController@index');

        $this->router = new Router(array($route));

        $container->set(get_class($this->router), $this->router);

        $config->set('app.router', 'Zapheus\Routing\Router');

        $this->container = $container->set(RoutingProvider::CONFIG, $config);

        $this->provider = new RoutingProvider;
    }

    /**
     * Tests ProviderInterface::register.
     *
     * @return void
     */
    public function testRegisterMethod()
    {
        $expected = new Route('GET', '/', 'HailController@index');

        $dispatcher = Application::DISPATCHER;

        $container = $this->provider->register($this->container);

        $dispatcher = $container->get($dispatcher);

        $result = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests ProviderInterface::register with a router from container.
     *
     * @return void
     */
    public function testRegisterMethodWithRouterFromContainer()
    {
        $provider = new RoutingProvider;

        $this->container->set(Application::ROUTER, $this->router);

        $expected = new Route('GET', '/', 'HailController@index');

        $dispatcher = Application::DISPATCHER;

        $container = $provider->register($this->container);

        $dispatcher = $container->get($dispatcher);

        $result = $dispatcher->dispatch('GET', '/');

        $this->assertEquals($expected, $result);
    }
}
