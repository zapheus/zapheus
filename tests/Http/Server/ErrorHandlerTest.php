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

        $this->self->setMessage($expect);

        $message = sprintf($expect, 'Zapheus\Contract\Http\Message\Response');

        $this->doExpectException('LogicException', $message);

        $server = array('REQUEST_METHOD' => 'GET');

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'roug.in';

        $server['SERVER_PORT'] = 8000;

        $factory = new Request;

        $factory->withServerParams($server);

        $this->self->handle($factory->make());
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new ErrorHandler;
    }
}
