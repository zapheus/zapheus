<?php

namespace Zapheus\Application;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Fixture\Http\Middlewares\SpoofMethod;
use Zapheus\Fixture\Providers\TestProvider;
use Zapheus\Http\Factory\Request as RequestFactory;
use Zapheus\Http\Factory\Response as ResponseFactory;
use Zapheus\Http\Message\Response;
use Zapheus\Http\Message\Stream;
use Zapheus\Http\ServerProvider;
use Zapheus\Routing\Dispatcher;
use Zapheus\Routing\Resolver;
use Zapheus\Routing\Route;
use Zapheus\Routing\Router;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends Testcase
{
    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_failed_if_post_fails_put_route()
    {
        $this->doExpectException('UnexpectedValueException');

        $this->doExpectExceptionMessage('Route "POST /users/1" not found');

        $app = $this->request('POST', '/users/1');

        /** @var \Zapheus\Contract\Http\Message\Request */
        $request = $app->get(Application::REQUEST);

        $app->handle($request);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_application_runs_normally()
    {
        $app = $this->request('GET', '/');

        $expect = 'Hello, world';

        $actual = $app->run();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_set_with_array()
    {
        $app = new Application;

        $app->config(array('framework' => 'Zapheus'));

        /** @var \Zapheus\Contract\Provider\Configuration */
        $config = $app->get(ServerProvider::CONFIG);

        $expect = 'Zapheus';

        $actual = $config->get('framework');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_set_with_file_path()
    {
        $app = new Application;

        $app->config(__DIR__ . '/../Fixture/Config');

        /** @var \Zapheus\Contract\Provider\Configuration */
        $config = $app->get(ServerProvider::CONFIG);

        $expect = 'Zapheus Framework';

        $actual = $config->get('test.settings.app_name');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_container_has_entry()
    {
        $interface = 'Zapheus\Contract\Http\Message\Response';

        $this->assertTrue($this->self->has($interface));
    }

    /**
     * @return void
     */
    public function test_passed_if_container_is_set()
    {
        $app = new Application;

        $container = new Container;

        $app->setContainer($container);

        $expect = $container;

        $actual = $app->getContainer();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_providers_are_returned()
    {
        $this->self->add($test = new TestProvider);

        $expect = array(get_class($test));

        $actual = $this->self->providers();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_request_has_middleware()
    {
        $this->self->add(new ServerProvider);

        $app = $this->request('GET', '/');

        $expect = 'Hello, world';

        $actual = $app->run();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_has_middleware()
    {
        $class = 'Zapheus\Contract\Http\Message\Request';

        $this->self->add(new ServerProvider);

        $app = $this->request('POST', '/json');

        /** @var \Zapheus\Contract\Http\Message\Request */
        $request = $app->get($class);

        $expect = array('application/json');

        $actual = $app->handle($request)->getHeader('Content-Type');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_run_with_resolver()
    {
        $app = $this->request('GET', '/');

        $resolver = new Resolver($app);

        $app->set(Application::RESOLVER, $resolver);

        $expect = 'Hello, world';

        $actual = $app->run();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_run_with_response()
    {
        $app = $this->request('GET', '/test');

        $resolver = new Resolver($app);

        $app->set(Application::RESOLVER, $resolver);

        $expect = 'Hello, Zapheus';

        $actual = $app->run();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_spoofed_method_matches()
    {
        $this->self->add(new ServerProvider(array(new SpoofMethod)));

        $app = $this->request('POST', '/users/1');

        $class = Application::REQUEST;

        /** @var \Zapheus\Contract\Http\Message\Request */
        $request = $app->get($class);

        $factory = new RequestFactory;

        $factory->setRequest($request);

        $factory->withParsedBody(array('_method' => 'PUT'));

        $app->set($class, $factory->make());

        /** @var \Zapheus\Contract\Http\Message\Request */
        $request = $app->get($class);

        $expect = 'Hello, world';

        $response = $app->handle($request);

        /** @var string */
        $actual = $response->getBody()->__toString();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Application;

        $routes = array();

        // Route with its handler as string-based ---
        $ctrl = $this->define(new HailController);

        $handler = $ctrl . '@greet';

        $routes[] = new Route('GET', '/', $handler);
        // ------------------------------------------

        // Same string-based route but with middleware ----------
        $items = array(new JsonMiddleware);

        $routes[] = new Route('POST', '/json', $handler, $items);
        // ------------------------------------------------------

        // Route with a middleware as a callback ---
        $cb = function ()
        {
            $factory = new ResponseFactory;

            /** @var resource */
            $stream = fopen('php://temp', 'r+');

            $body = new Stream($stream);

            $body->write('Hello, Zapheus');

            $factory->withBody($body);

            $factory->withStatus(200);

            return $factory->make();
        };

        $routes[] = new Route('GET', '/test', $cb);
        // -----------------------------------------

        // Route with a "PUT" as spoofed HTTP method --------
        $routes[] = new Route('PUT', '/users/:id', $handler);

        $router = new Router($routes);
        // --------------------------------------------------

        // Initialize the route dispatcher ---
        $dispatch = new Dispatcher($router);

        $class = Application::DISPATCHER;

        $this->self->set($class, $dispatch);
        // -----------------------------------

        // Initialize default response with headers --------
        $headers = array('X-Framework' => array('Zapheus'));

        $response = new Response(200, $headers);

        $class = Application::RESPONSE;

        $this->self->set($class, $response);
        // -------------------------------------------------
    }
}
