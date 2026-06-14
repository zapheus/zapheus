<?php

namespace Zapheus\Contract\Renderer;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface Renderer
{
    /**
     * Renders a file from a specified template.
     *
     * @param string               $plate
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($plate, array $data = array());
}
