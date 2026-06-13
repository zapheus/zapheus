<?php

namespace Zapheus\Routing;

use Zapheus\Container\ReflectionContainer;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Controllers\LaudController;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Routing\Dispatcher
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_route_is_not_found()
    {
        $this->doExpectException('UnexpectedValueException');

        $resolver = $this->self->dispatch('GET', '/404');
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatched_with_array_handler()
    {
        $expect = 'Hello, world';

        $route = $this->self->dispatch('GET', 'test/wow');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatched_with_closure()
    {
        $expect = 'Hello everyone! I am Royce';

        $route = $this->self->dispatch('GET', 'test/Royce');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatched_with_default_value()
    {
        $expect = 'Hello, my name is Royce and this is a test.';

        $route = $this->self->dispatch('GET', 'helloo/Royce');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_is_dispatched()
    {
        $expect = 'Hello, world';

        $route = $this->self->dispatch('GET', '/greeeeet');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_similar_routes_are_matched()
    {
        $expect = 'Hello everyone! I am Royce';

        $route = $this->self->dispatch('GET', 'test/Royce');

        $resolver = new Resolver(new ReflectionContainer);

        $actual = $resolver->resolve($route);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $hail = new HailController;
        $laud = null;

        $laud = new LaudController($hail);

        $router = new Router;

        $router->get('/greeeeet', get_class($hail) . '@greet');

        $router->get('/test/wow', array(get_class($hail), 'greet'));

        $router->get('/helloo/{name}', function ($name = 'Doe')
        {
            $message = sprintf('my name is %s', $name);

            return 'Hello, ' . $message . ' and this is a test.';
        });

        $router->get('/test/{name}', function ($name)
        {
            return 'Hello everyone! I am ' . $name;
        });

        $this->self = new Dispatcher($router);
    }
}
