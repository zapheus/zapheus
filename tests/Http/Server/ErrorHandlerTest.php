<?php

namespace Zapheus\Http\Server;

use Zapheus\Http\Factory\Request;
use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ErrorHandlerTest extends Testcase
{
    /**
     * @var \Zapheus\Http\Server\ErrorHandler
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_custom_error_message()
    {
        $expect = 'Custom error: "%s"';

        $class = 'Zapheus\Contract\Http\Message\Response';

        $this->self->setMessage($expect);

        $message = sprintf($expect, $class);

        $this->doExpectExceptionMessage($message);

        // Simulate data as $_SERVER ---------
        $server = array('REQUEST_URI' => '/');

        $server['REQUEST_METHOD'] = 'GET';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = '8000';
        // -----------------------------------

        $factory = new Request;

        $factory->withServerParams($server);

        $request = $factory->make();

        $this->self->handle($request);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new ErrorHandler;
    }
}
