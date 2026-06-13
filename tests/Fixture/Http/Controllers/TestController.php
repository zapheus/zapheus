<?php

namespace Zapheus\Fixture\Http\Controllers;

/**
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
     * @param \Zapheus\Fixture\Http\Controllers\LaudController $laud
     */
    public function __construct(LaudController $laud)
    {
        $this->laud = $laud;
    }

    /**
     * @return string
     */
    public function greet()
    {
        return $this->laud->greet();
    }
}
