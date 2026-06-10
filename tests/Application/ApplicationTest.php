<?php

namespace Zapheus\Application;

use Zapheus\Application;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Fixture\Providers\TestProvider;
use Zapheus\Http\Message\Response;
use Zapheus\Http\Message\ResponseFactory;
use Zapheus\Http\ServerProvider;
use Zapheus\Routing\Dispatcher;
use Zapheus\Routing\Resolver;
use Zapheus\Routing\Route;
use Zapheus\Routing\Router;

/**
 * Application Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends AbstractTestCase
{
    /**
     * Sets up the application instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        parent::setUp();

        $this->app = $this->application();

        $controller = $this->define(new HailController);

        $handler = (string) $controller . '@greet';

        $route = new Route('GET', '/', $handler);

        $json = new Route('POST', '/json', $handler, new JsonMiddleware);

        $test = new Route('GET', '/test', function ()
        {
            $factory = new ResponseFactory;

            $factory->write('Hello, Zapheus');

            return $factory->make();
        });

        $router = new Router(array($route, $json, $test));

        $dispatcher = new Dispatcher($router);

        $this->app->set(Application::DISPATCHER, $dispatcher);

        $headers = array('X-Framework' => array('Zapheus'));

        $response = new Response(200, (array) $headers);

        $this->app->set(Application::RESPONSE, $response);
    }

    /**
     * Tests Application::handle with middleware.
     *
     * @return void
     */
    public function testHandleMethodWithMiddleware()
    {
        $this->app->add(new ServerProvider);

        $app = $this->request('GET', '/');

        $expected = (string) 'Hello, world';

        $result = (string) $app->run();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with middleware inside a route.
     *
     * @return void
     */
    public function testHandleMethodWithMiddlewareInsideRoute()
    {
        $interface = 'Zapheus\Http\Message\RequestInterface';

        $this->app->add(new ServerProvider);

        $app = $this->request('POST', '/json');

        $request = $app->get($interface);

        $expected = array('application/json');

        $result = $app->handle($request)->header('Content-Type');

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

        $app->set(Application::RESOLVER, $resolver);

        $expected = 'Hello, world';

        $result = (string) $app->run();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests AbstractApplication::run with another response.
     *
     * @return void
     */
    public function testRunMethodWithResponse()
    {
        $app = $this->request('GET', '/test');

        $resolver = new Resolver($app);

        $app->set(Application::RESOLVER, $resolver);

        $expected = 'Hello, Zapheus';

        $result = (string) $app->run();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::config with string data.
     *
     * @return void
     */
    public function testConfigMethodWithStringData()
    {
        $application = $this->application();

        $application->config(__DIR__ . '/../Fixture/Config');

        $config = $application->get(ServerProvider::CONFIG);

        $expected = 'Zapheus Framework';

        $result = $config->get('test.settings.app_name');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::config with array data.
     *
     * @return void
     */
    public function testConfigMethodWithArrayData()
    {
        $application = $this->application();

        $application->config(array('framework' => 'Zapheus'));

        $config = $application->get(ServerProvider::CONFIG);

        $expected = (string) 'Zapheus';

        $result = (string) $config->get('framework');

        $this->assertEquals($expected, $result);
    }
}
