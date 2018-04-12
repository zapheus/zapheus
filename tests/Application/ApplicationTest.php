<?php

namespace Zapheus\Application;

use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Providers\TestProvider;
use Zapheus\Http\Message\Response;
use Zapheus\Http\Server\RoutingHandler;
use Zapheus\Http\ServerProvider;
use Zapheus\Routing\Dispatcher;
use Zapheus\Routing\Resolver;
use Zapheus\Routing\Route;
use Zapheus\Routing\Router;

/**
 * Application Test
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends AbstractTestCase
{
    /**
     * Sets up the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->app = $this->application();

        $controller = $this->define(new HailController);

        $handler = (string) $controller . '@greet';

        $route = new Route('GET', '/', $handler);

        $router = new Router(array($route));

        $dispatcher = new Dispatcher($router);

        $this->app->set(RoutingHandler::DISPATCHER, $dispatcher);

        $headers = array('X-Framework' => array('Zapheus'));

        $response = new Response(200, (array) $headers);

        $this->app->set(RoutingHandler::RESPONSE, $response);
    }

    /**
     * Tests Application::has.
     *
     * @return void
     */
    public function testHandleMethodWithMiddleware()
    {
        $this->app->add(new ServerProvider);

        $app = $this->request('GET', '/');

        $expected = 'Hello, world';

        $result = (string) $app->run();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::has.
     *
     * @return void
     */
    public function testHasMethod()
    {
        $interface = 'Zapheus\Http\Message\ResponseInterface';

        $this->assertTrue($this->app->has($interface));
    }

    /**
     * Tests Application::providers.
     *
     * @return void
     */
    public function testProvidersMethod()
    {
        $this->app->add($test = new TestProvider);

        $expected = array(get_class($test));

        $result = $this->app->providers();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests AbstractApplication::run.
     *
     * @return void
     */
    public function testRunMethod()
    {
        $app = $this->request('GET', '/');

        $expected = 'Hello, world';

        $result = (string) $app->run();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests AbstractApplication::run with another resolver.
     *
     * @return void
     */
    public function testRunMethodWithResolver()
    {
        $app = $this->request('GET', '/');

        $resolver = new Resolver($app);

        $app->set(RoutingHandler::RESOLVER, $resolver);

        $expected = 'Hello, world';

        $result = (string) $app->run();

        $this->assertEquals($expected, $result);
    }
}
