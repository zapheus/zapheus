<?php

namespace Zapheus\Application;

use Zapheus\Coordinator;
use Zapheus\Fixture\Http\Controllers\HailController;

/**
 * Coordinator Test
 *
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CoordinatorTest extends AbstractTestCase
{
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
     * Tests AbstractApplication::run without dependencies.
     *
     * @return void
     */
    public function testRunMethodWithoutDependencies()
    {
        $app = $this->request('GET', '/hello');

        $expected = 'Hello, world';

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
        $this->doExpectException('BadMethodCallException');

        $this->app->test();
    }

    /**
     * Sets up the application instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        parent::doSetUp();

        $this->app = new Coordinator($this->application());

        $this->define(new HailController);

        $hail = 'Zapheus\Fixture\Http\Controllers\HailController';

        $laud = 'Zapheus\Fixture\Http\Controllers\LaudController';

        $this->app->connect('/greet', $laud . '@greet');

        $this->app->delete('/greet', $laud . '@greet');

        $this->app->get('/greet', $laud . '@greet');

        $this->app->get('/hello', $hail . '@greet');

        $this->app->head('/greet', $laud . '@greet');

        $this->app->options('/greet', $laud . '@greet');

        $this->app->patch('/greet', $laud . '@greet');

        $this->app->post('/greet', $laud . '@greet');

        $this->app->purge('/greet', $laud . '@greet');

        $this->app->put('/greet', $laud . '@greet');

        $this->app->trace('/greet', $laud . '@greet');
    }
}
