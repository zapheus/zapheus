<?php

namespace Zapheus\Renderer;

use Zapheus\Contract\Container\Writable;
use Zapheus\Contract\Provider\Provider;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererProvider implements Provider
{
    const RENDERER = 'Zapheus\Contract\Renderer\Renderer';

    /**
     * @var string[]
     */
    protected $paths = array();

    /**
     * Initializes the renderer instance.
     *
     * @param array<string, string>|string $paths
     */
    public function __construct($paths = array())
    {
        $this->paths = is_array($paths) ? $paths : array($paths);
    }

    /**
     * Registers the bindings in the container.
     *
     * @param \Zapheus\Contract\Container\Writable $container
     *
     * @return \Zapheus\Contract\Container\Writable
     */
    public function register(Writable $container)
    {
        $config = $container->get(self::CONFIG);

        $paths = $config->get('app.views', $this->paths);

        $renderer = new Renderer(is_array($paths) ? $paths : array($paths));

        return $container->set(self::RENDERER, $renderer);
    }
}
