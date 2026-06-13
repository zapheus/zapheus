<?php

namespace Zapheus\Fixture\Http\Controllers;

/**
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
     * @param \Zapheus\Fixture\Http\Controllers\HailController $hail
     */
    public function __construct(HailController $hail)
    {
        $this->hail = $hail;
    }

    /**
     * @return string
     */
    public function greet()
    {
        $text = $this->hail->greet();

        return $text . ' and people';
    }
}
