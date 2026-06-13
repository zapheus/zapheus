<?php

namespace Zapheus\Application;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Fixture\Providers\TestProvider;
use Zapheus\Http\Factory\Response as ResponseFactory;
use Zapheus\Http\Message\Response;
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
class ApplicationTest extends AbstractTestCase
{
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
        $application = $this->application();

        $application->config(array('framework' => 'Zapheus'));

        $config = $application->get(ServerProvider::CONFIG);

        $expect = 'Zapheus';

        $actual = $config->get('framework');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_set_with_file_path()
    {
        $application = $this->application();

        $application->config(__DIR__ . '/../Fixture/Config');

        $config = $application->get(ServerProvider::CONFIG);

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
        $application = $this->application();

        $container = new Container;

        $application->setContainer($container);

        $expect = $container;

        $actual = $application->getContainer();

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
        $interface = 'Zapheus\Contract\Http\Message\Request';

        $this->self->add(new ServerProvider);

        $app = $this->request('POST', '/json');

        $request = $app->get($interface);

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
    protected function doSetUp()
    {
        parent::doSetUp();

        $this->self = $this->application();

        $controller = $this->define(new HailController);

        $handler = $controller . '@greet';

        $route = new Route('GET', '/', $handler);

        $json = new Route('POST', '/json', $handler, new JsonMiddleware);

        $test = new Route('GET', '/test', function ()
        {
            $factory = new ResponseFactory;

            $stream = fopen('php://temp', 'r+');

            ! $stream && $stream = null;

            $body = new \Zapheus\Http\Message\Stream($stream);

            $body->write('Hello, Zapheus');

            $factory->withBody($body);

            $factory->withStatus(200);

            return $factory->make();
        });

        $router = new Router(array($route, $json, $test));

        $dispatcher = new Dispatcher($router);

        $this->self->set(Application::DISPATCHER, $dispatcher);

        $headers = array('X-Framework' => array('Zapheus'));

        $response = new Response(200, $headers);

        $this->self->set(Application::RESPONSE, $response);
    }
}
