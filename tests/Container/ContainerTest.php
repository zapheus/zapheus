<?php

namespace Zapheus\Container;

use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Testcase;

/**
 * Container Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Zapheus\Container\ContainerInterface
     */
    protected $container;

    /**
     * Tests ContainerInterface::get method.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $hail = new HailController;

        $name = get_class($hail);

        $this->container->set('hail', $hail);

        $instance = $this->container->get('hail');

        $this->assertInstanceOf($name, $instance);
    }

    /**
     * Tests ContainerInterface::get method with NotFoundException.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $exception = 'Zapheus\Container\NotFoundException';

        $this->doExpectException($exception);

        $this->container->get('hail');
    }

    /**
     * Tests ContainerInterface::set method.
     *
     * @return void
     */
    public function testSetMethod()
    {
        $this->container->set('hail', new HailController);

        $this->assertTrue($this->container->has('hail'));
    }

    /**
     * Sets up the container instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
    }
}
