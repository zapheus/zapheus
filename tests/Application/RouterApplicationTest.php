<?php

namespace Zapheus\Application;

use Zapheus\Fixture\Http\Controllers\HailController;
use Zapheus\Fixture\Http\Controllers\LaudController;
use Zapheus\Http\Message\ServerRequest;

/**
 * Router Application Test
 *
 * @package Zapheus
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RouterApplicationTest extends TestCase
{
    /**
     * Sets up the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->application = new RouterApplication;

        $instance = get_class(new LaudController(new HailController));

        $this->application->connect('/greet', $instance . '@greet');
        $this->application->delete('/greet', $instance . '@greet');
        $this->application->get('/greet', $instance . '@greet');
        $this->application->head('/greet', $instance . '@greet');
        $this->application->options('/greet', $instance . '@greet');
        $this->application->patch('/greet', $instance . '@greet');
        $this->application->post('/greet', $instance . '@greet');
        $this->application->purge('/greet', $instance . '@greet');
        $this->application->put('/greet', $instance . '@greet');
        $this->application->trace('/greet', $instance . '@greet');
    }

    /**
     * Tests AbstractApplication::run.
     *
     * @return void
     */
    public function testRunMethod()
    {
        $app = $this->request('GET', '/greet');

        $expected = 'Hello, world and people';

        $result = (string) $app->run();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests an unknown method.
     *
     * @return void
     */
    public function testUnknownMethod()
    {
        $this->setExpectedException('BadMethodCallException');

        $this->application->test();
    }
}
