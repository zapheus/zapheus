<?php

namespace Zapheus\Renderer;

use Zapheus\Container\Container;
use Zapheus\Provider\Configuration;
use Zapheus\Testcase;

/**
 * Renderer Provider Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererProviderTest extends Testcase
{
    /**
     * @var \Zapheus\Container\WritableInterface
     */
    protected $container;

    /**
     * @var \Zapheus\Provider\ProviderInterface
     */
    protected $provider;

    /**
     * Tests ProviderInterface::register.
     *
     * @return void
     */
    public function testRegisterMethod()
    {
        $container = $this->provider->register($this->container);

        $renderer = $container->get(RendererProvider::RENDERER);

        $expected = 'Lorem ipsum dolor sit amet';

        $result = $renderer->render('loremipsum');

        $this->assertEquals($expected, $result);
    }

    /**
     * Sets up the provider instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        list($config, $container) = array(new Configuration, new Container);

        $config->set('app.views', __DIR__ . '/../Fixture/Views');

        $this->container = $container->set(RendererProvider::CONFIG, $config);

        $this->provider = new RendererProvider;
    }
}
