<?php

namespace Slytherium\Container;

use Slytherium\Fixture\Http\Controllers\HailController;
use Slytherium\Fixture\Http\Controllers\LaudController;

/**
 * Composite Container Test
 *
 * @package Slytherium
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CompositeContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Slytherium\Container\ContainerInterface
     */
    protected $container;

    /**
     * Sets up the container instance.
     *
     * @return void
     */
    public function setUp()
    {
        list($first, $second) = array(new Container, new Container);

        $container = new CompositeContainer;

        $first->set('simple', $simple = new HailController);

        $second->set('extended', new LaudController($simple));

        $this->container = $container->add($first)->add($second);
    }

    /**
     * Tests ContainerInterface::get.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $name = get_class(new HailController);

        $instance = $this->container->get('simple');

        $this->assertInstanceOf($name, $instance);
    }

    /**
     * Tests ContainerInterface::get method with NotFoundException.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $exception = 'Slytherium\Container\NotFoundException';

        $this->setExpectedException($exception);

        $this->container->get('test');
    }
}
