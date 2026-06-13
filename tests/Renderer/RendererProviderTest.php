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
    protected $app;

    /**
     * @var \Zapheus\Renderer\RendererProvider
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_renderer_is_registered()
    {
        $app = $this->self->register($this->app);

        $contract = RendererProvider::RENDERER;

        /** @var \Zapheus\Contract\Renderer\Renderer */
        $self = $app->get($contract);

        $expect = 'Lorem ipsum dolor sit amet';

        $actual = $self->render('loremipsum');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $config = new Configuration;

        $app = new Container;

        $path = __DIR__ . '/../Fixture/Views';

        $config->set('app.views', $path);

        $class = RendererProvider::CONFIG;

        $this->app = $app->set($class, $config);

        $this->self = new RendererProvider;
    }
}
