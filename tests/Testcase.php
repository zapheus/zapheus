<?php

namespace Zapheus;

use LegacyPHPUnit\TestCase as Legacy;

/**
 * @codeCoverageIgnore
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Testcase extends Legacy
{
    /**
     * @param string $exception
     *
     * @return void
     */
    public function doExpectException($exception)
    {
        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'expectException'))
        {
            /** @phpstan-ignore-next-line */
            $this->expectException($exception);

            return;
        }

        /** @phpstan-ignore-next-line */
        $this->setExpectedException($exception);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function doExpectExceptionMessage($message)
    {
        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'expectExceptionMessage'))
        {
            $this->expectExceptionMessage($message);

            return;
        }

        /** @phpstan-ignore-next-line */
        $this->setExpectedException('Exception', $message);
    }
}
