<?php

namespace Zapheus\Renderer;

/**
 * Renderer Interface
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface RendererInterface
{
    /**
     * Renders a file from a specified template.
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($template, array $data = array());
}
