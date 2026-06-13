<?php

namespace Zapheus\Container;

use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Controllers\LaudController;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainerTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Container\Container
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_class_does_not_exist()
    {
        $exception = 'Zapheus\Container\NotFoundException';

        $this->doExpectException($exception);

        $this->self->get('hail');
    }

    /**
     * @return void
     */
    public function test_passed_if_class_created_from_container()
    {
        $name = get_class(new HailController);

        $instance = $this->self->get($name);

        $this->assertInstanceOf($name, $instance);
    }

    /**
     * @return void
     */
    public function test_passed_if_dependencies_are_resolved()
    {
        $laud = new LaudController(new HailController);

        $name = get_class($laud);

        $instance = $this->self->get($name);

        $this->assertInstanceOf($name, $instance);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new ReflectionContainer;
    }
}
