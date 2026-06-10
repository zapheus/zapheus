<?php

namespace Zapheus\Renderer;

use Zapheus\Container\WritableInterface;
use Zapheus\Provider\ProviderInterface;

/**
 * Renderer Provider
 *
 * @package App
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererProvider implements ProviderInterface
{
    const RENDERER = 'Zapheus\Renderer\RendererInterface';

    /**
     * @var string[]
     */
    protected $paths = array();

    /**
     * Initializes the renderer instance.
     *
     * @param array|string $paths
     */
    public function __construct($paths = array())
    {
        $this->paths = (array) $paths;
    }

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Container\WritableInterface $container
     *
     * @return \Zapheus\Container\ContainerInterface
     */
    public function register(WritableInterface $container)
    {
        $config = $container->get(self::CONFIG);

        $paths = $config->get('app.views', $this->paths);

        $renderer = new Renderer((array) $paths);

        return $container->set(self::RENDERER, $renderer);
    }
}
