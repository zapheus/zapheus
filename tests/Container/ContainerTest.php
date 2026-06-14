<?php

namespace Zapheus\Container;

use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Container\Writable
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_id_not_found_in_container()
    {
        $exception = 'Zapheus\Container\NotFoundException';

        $this->doExpectException($exception);

        $this->self->get('hail');
    }

    /**
     * @return void
     */
    public function test_passed_if_container_exception_instantiated()
    {
        $actual = new ContainerException;

        $this->assertInstanceOf('InvalidArgumentException', $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_container_returns_entry()
    {
        $hail = new HailController;

        $name = get_class($hail);

        $this->self->set('hail', $hail);

        $instance = $this->self->get('hail');

        $this->assertInstanceOf($name, $instance);
    }

    /**
     * @return void
     */
    public function test_passed_if_container_sets_entry()
    {
        $this->self->set('hail', new HailController);

        $this->assertTrue($this->self->has('hail'));
    }

    /**
     * @return void
     */
    public function test_passed_if_not_found_exception_instantiated()
    {
        $actual = new NotFoundException;

        $this->assertInstanceOf('Zapheus\Container\ContainerException', $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Container;
    }
}
