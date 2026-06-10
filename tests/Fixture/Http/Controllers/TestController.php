<?php

namespace Zapheus\Fixture\Http\Controllers;

/**
 * Test Controller
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TestController
{
    /**
     * @var \Zapheus\Fixture\Http\Controllers\LaudController
     */
    protected $laud;

    /**
     * Initializes the controller instance.
     *
     * @param \Zapheus\Fixture\Http\Controllers\LaudController $laud
     */
    public function __construct(LaudController $laud)
    {
        $this->laud = $laud;
    }

    /**
     * Returns the text "Hello, world and people".
     *
     * @return string
     */
    public function greet()
    {
        return $this->laud->greet();
    }
}
