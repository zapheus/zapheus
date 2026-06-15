<?php

namespace Zapheus\Routing;

use Zapheus\Fixture\Http\Middlewares\JsonMiddleware;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends Testcase
{
    /**
     * @var \Zapheus\Routing\Router
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_route_is_added()
    {
        $this->self->get('/greet/:id', 'HailController@greet');

        $route = new Route('GET', 'greet/:id', 'HailController@greet');

        $this->assertTrue($this->self->has($route));
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_are_returned()
    {
        $expect = 10;

        $this->self->connect('/', 'HailController@greet');
        $this->self->delete('/', 'HailController@greet');
        $this->self->get('/', 'HailController@greet');
        $this->self->head('/', 'HailController@greet');
        $this->self->options('/', 'HailController@greet');
        $this->self->patch('/', 'HailController@greet');
        $this->self->post('/', 'HailController@greet');
        $this->self->purge('/', 'HailController@greet');
        $this->self->put('/', 'HailController@greet');
        $this->self->trace('/', 'HailController@greet');

        $actual = $this->self->routes();

        $this->assertCount($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_is_added_with_callable()
    {
        $callback = function ()
        {
            return;
        };

        $this->self->get('/test', 'HailController@greet', $callback);

        $routes = $this->self->routes();

        $route = $routes[0];

        $expect = 1;

        $actual = $route->getMiddlewares();

        $this->assertCount($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_is_added_with_middleware()
    {
        $middleware = new JsonMiddleware;

        $this->self->get('/test', 'HailController@greet', array($middleware));

        $routes = $this->self->routes();

        $route = $routes[0];

        $expect = 1;

        $actual = $route->getMiddlewares();

        $this->assertCount($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_is_added_with_single()
    {
        $class = 'Zapheus\Fixture\Http\Middlewares\JsonMiddleware';

        $this->self->get('/test', 'HailController@greet', $class);

        $routes = $this->self->routes();

        $route = $routes[0];

        $expect = 1;

        $actual = $route->getMiddlewares();

        $this->assertCount($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_is_added_with_namespace()
    {
        $this->self->setNamespace('Zapheus\Fixture\Http\Controllers');

        $this->self->get('/test', 'HailController@greet');

        $expect = 'Zapheus\Fixture\Http\Controllers\HailController@greet';

        $routes = $this->self->routes();

        $route = $routes[0];

        $actual = $route->getHandler();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Router;
    }
}
