<?php

namespace Zapheus\Renderer;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Renderer\Renderer
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_template_file_not_found()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->self->render('InvalidFile');
    }

    /**
     * @return void
     */
    public function test_passed_if_template_is_rendered()
    {
        $expect = 'Lorem ipsum dolor sit amet';

        $actual = $this->self->render('loremipsum');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/../Fixture/Views';

        $this->self = new Renderer($path);
    }
}
