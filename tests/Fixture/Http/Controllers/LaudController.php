<?php

namespace Zapheus\Fixture\Http\Controllers;

/**
 * Laud Controller
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LaudController
{
    /**
     * @var \Zapheus\Fixture\Http\Controllers\HailController
     */
    protected $hail;

    /**
     * Initializes the controller instance.
     *
     * @param \Zapheus\Fixture\Http\Controllers\HailController $hail
     */
    public function __construct(HailController $hail)
    {
        $this->hail = $hail;
    }

    /**
     * Returns the text "Hello, world and people".
     *
     * @return string
     */
    public function greet()
    {
        $text = $this->hail->greet();

        return $text . ' and people';
    }
}
