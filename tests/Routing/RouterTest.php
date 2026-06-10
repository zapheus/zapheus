<?php

namespace Zapheus\Routing;

use Zapheus\Testcase;

/**
 * Router Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends Testcase
{
    /**
     * @var \Zapheus\Routing\RouterInterface
     */
    protected $router;

    /**
     * Tests RouterInterface::add.
     *
     * @return void
     */
    public function testAddMethod()
    {
        $this->router->get('/greet/:id', 'HailController@greet');

        $route = new Route('GET', 'greet/:id', 'HailController@greet');

        $this->assertTrue($this->router->has($route));
    }

    /**
     * Tests RouterInterface::routes.
     *
     * @return void
     */
    public function testRoutesMethod()
    {
        $expected = 10;

        $this->router->connect('/', 'HailController@greet');
        $this->router->delete('/', 'HailController@greet');
        $this->router->get('/', 'HailController@greet');
        $this->router->head('/', 'HailController@greet');
        $this->router->options('/', 'HailController@greet');
        $this->router->patch('/', 'HailController@greet');
        $this->router->post('/', 'HailController@greet');
        $this->router->purge('/', 'HailController@greet');
        $this->router->put('/', 'HailController@greet');
        $this->router->trace('/', 'HailController@greet');

        $result = $this->router->routes();

        $this->assertCount($expected, $result);
    }

    /**
     * Sets up the router instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->router = new Router;
    }
}
