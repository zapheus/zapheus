<?php

namespace Zapheus\Routing;

use Zapheus\Container\Container;
use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Controllers\LaudController;
use Zapheus\Fixture\Http\Controllers\TestController;
use Zapheus\Fixture\Http\Middlewares\LastMiddleware;
use Zapheus\Testcase;

/**
 * Resolver Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResolverTest extends Testcase
{
    /**
     * @var \Zapheus\Routing\ResolverInterface
     */
    protected $resolver;

    /**
     * Tests ResolverInterface::resolve.
     *
     * @return void
     */
    public function testResolveMethod()
    {
        $factory = new RouteFactory;

        $factory->uri('/test');

        $instance = 'Zapheus\Fixture\Http\Controllers\TestController';

        $expected = 'Hello, world and people';

        $factory->method('GET');

        $factory->handler(array($instance, 'greet'));

        $factory->middlewares(array(new LastMiddleware));

        $route = $factory->make();

        $result = $this->resolver->resolve($route);

        $this->assertEquals($expected, $result);
    }

    /**
     * Sets up the resolver instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $container = new Container;

        $laud = new LaudController(new HailController);

        $test = new TestController($laud);

        $container->set(get_class($test), $test);

        $this->resolver = new Resolver($container);
    }
}
