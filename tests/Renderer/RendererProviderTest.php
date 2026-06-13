<?php

namespace Zapheus\Renderer;

use Zapheus\Container\Container;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererProviderTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Container\Writable
     */
    protected $container;

    /**
     * @var \Zapheus\Contract\Provider\Provider
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_renderer_is_registered()
    {
        $container = $this->self->register($this->container);

        $renderer = $container->get(RendererProvider::RENDERER);

        $expect = 'Lorem ipsum dolor sit amet';

        $actual = $renderer->render('loremipsum');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $config    = new Configuration;
        $container = new Container;

        $config->set('app.views', __DIR__ . '/../Fixture/Views');

        $this->container = $container->set(RendererProvider::CONFIG, $config);

        $this->self = new RendererProvider;
    }
}
