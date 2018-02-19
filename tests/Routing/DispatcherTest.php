<?php

namespace Zapheus\Routing;

use Zapheus\Container\ReflectionContainer;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Controllers\LaudController;
use Zapheus\Routing\Dispatcher;
use Zapheus\Routing\Resolver;
use Zapheus\Routing\Router;

/**
 * Dispatcher Test
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zapheus\Routing\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * Sets up the dispatcher instance.
     *
     * @return void
     */
    public function setUp()
    {
        list($hail, $laud) = array(new HailController, null);

        $laud = new LaudController($hail);

        $router = new Router;

        $router->get('/greeeeet', get_class($hail) . '@greet');

        $router->get('/wow', array(get_class($hail), 'greet'));

        $router->get('/helloo/{name}', function ($name = 'Doe') {
            $message = (string) sprintf('my name is %s', $name);

            return 'Hello, ' . $message . ' and this is a test.';
        });

        $this->dispatcher = new Dispatcher($router);
    }

    /**
     * Tests DispatcherInterface::dispatch.
     *
     * @return void
     */
    public function testDispatchMethod()
    {
        $expected = (string) 'Hello, world';

        $route = $this->dispatcher->dispatch('GET', '/greeeeet');

        $container = new ReflectionContainer;

        $resolver = new Resolver;

        $result = $resolver->resolve($container, $route);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::dispatch with closure as handler.
     *
     * @return void
     */
    public function testDispatchMethodWithClosureAsHandler()
    {
        $expected = 'Hello, my name is Royce and this is a test.';

        $route = $this->dispatcher->dispatch('GET', 'helloo/Royce');

        $container = new ReflectionContainer;

        $resolver = new Resolver;

        $result = $resolver->resolve($container, $route);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::dispatch with handler as an array.
     *
     * @return void
     */
    public function testDispatchMethodWithHandlerAsArray()
    {
        $expected = (string) 'Hello, world';

        $route = $this->dispatcher->dispatch('GET', 'wow');

        $container = new ReflectionContainer;

        $resolver = new Resolver;

        $result = $resolver->resolve($container, $route);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::dispatch with \UnexpectedValueException.
     *
     * @return void
     */
    public function testDispatchMethodWithUnexpectedValueException()
    {
        $this->setExpectedException('UnexpectedValueException');

        $resolver = $this->dispatcher->dispatch('GET', '/404');
    }
}
