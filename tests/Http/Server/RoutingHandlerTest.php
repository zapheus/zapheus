<?php

namespace Zapheus\Http\Server;

use Zapheus\Application;
use Zapheus\Container\Container;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Http\Factory\Request;
use Zapheus\Routing\Route;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingHandlerTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Server\RoutingHandler
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_route_from_attribute()
    {
        // Simulate data as $_SERVER ---------
        $server = array('REQUEST_URI' => '/');

        $server['REQUEST_METHOD'] = 'GET';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = '8000';
        // -----------------------------------

        $factory = new Request;

        $factory->withServerParams($server);

        $route = new Route('GET', '/', 'Zapheus\Fixture\Http\Controllers\HailController@greet');

        $factory->withAttribute(Application::ROUTE_ATTRIBUTE, $route);

        $expect = 'Hello, world';

        $response = $this->self->handle($factory->make());

        /** @var string */
        $actual = $response->getBody()->__toString();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $container = new Container;

        $controller = new HailController;

        $container->set(get_class($controller), $controller);

        $response = new \Zapheus\Http\Message\Response;

        $container->set(\Zapheus\Application::RESPONSE, $response);

        $this->self = new RoutingHandler($container);
    }
}
