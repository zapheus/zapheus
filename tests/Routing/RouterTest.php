<?php

namespace Zapheus\Routing;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Routing\Router
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
    protected function doSetUp()
    {
        $this->self = new Router;
    }
}
