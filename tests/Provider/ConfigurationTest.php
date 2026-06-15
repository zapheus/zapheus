<?php

namespace Zapheus\Provider;

use Zapheus\Testcase;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ConfigurationTest extends Testcase
{
    /**
     * @var \Zapheus\Contract\Provider\Configuration
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_all_config_is_dotified()
    {
        $expect = array('user.name' => 'Rougin');

        $actual = $this->self->all(true);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_all_config_is_returned()
    {
        $expect = array('user' => array('name' => 'Rougin'));

        $actual = $this->self->all();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_key_is_returned()
    {
        $expect = 'Rougin';

        $actual = $this->self->get('user.name');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_loads_from_path()
    {
        $expect = 'Zapheus Framework';

        $path = str_replace('Provider', 'Fixture', __DIR__) . '/Config';

        $this->self->load($path);

        $actual = $this->self->get('app.app_name');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_loads_recursively()
    {
        $expect = 'An independent and framework-friendly framework.';

        $path = str_replace('Provider', 'Fixture', __DIR__) . '/Config';

        $this->self->load($path);

        $actual = $this->self->get('test.settings.description');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_value_is_set()
    {
        $expect = 'Zapheus';

        $this->self->set('user.name', $expect);

        $actual = $this->self->get('user.name');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_default_dotified_returned()
    {
        $expect = array('user.paths' => array());

        $this->self->set('app.user.paths', array());

        $actual = $this->self->get('app', array(), true);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_default_returned_for_non_array()
    {
        $expect = 'fallback';

        $actual = $this->self->get('user.name.extra', 'fallback');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_default_returned_for_null_value()
    {
        $this->self->set('app.name', null);

        $expect = 'fallback';

        $actual = $this->self->get('app.name', 'fallback');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_default_returned_for_unknown()
    {
        $expect = array();

        $this->self->set('app.views', array());

        $actual = $this->self->get('app.views');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_key_is_set()
    {
        $actual = $this->self->set('', 'value');

        $this->assertInstanceOf('Zapheus\Provider\Configuration', $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_flatten_skips_non_string_key()
    {
        /** @phpstan-ignore-next-line */
        $config = new Configuration(array(0 => 'skipped'));

        $actual = $config->all(true);

        $this->assertEquals(array(), $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $data = array('user' => array());

        $data['user']['name'] = 'Rougin';

        $this->self = new Configuration($data);
    }
}
