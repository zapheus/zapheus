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
     * @param string|string[] $paths
     */
    public function __construct($paths = array())
    {
        if (is_string($paths))
        {
            $paths = array($paths);
        }

        $this->paths = $paths;
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
        /** @var \Zapheus\Contract\Provider\Configuration */
        $config = $container->get(self::CONFIG);

        /** @var string|string[] */
        $paths = $config->get('app.views', $this->paths);

        $self = new Renderer($paths);

        $class = self::RENDERER;

        return $container->set($class, $self);
    }
}
