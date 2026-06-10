<?php

namespace Zapheus\Renderer;

use Zapheus\Testcase;

/**
 * Renderer Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTest extends Testcase
{
    /**
     * @var \Zapheus\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * Tests RendererInterface::render.
     *
     * @return void
     */
    public function testRenderMethod()
    {
        $expected = 'Lorem ipsum dolor sit amet';

        $result = $this->renderer->render('loremipsum');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RendererInterface::render with \InvalidArgumentException.
     *
     * @return void
     */
    public function testRenderMethodWithInvalidArgumentException()
    {
        $this->doSetExpectedException('InvalidArgumentException');

        $this->renderer->render('InvalidFile');
    }

    /**
     * Sets up the renderer instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/../Fixture/Views';

        $this->renderer = new Renderer($path);
    }
}
