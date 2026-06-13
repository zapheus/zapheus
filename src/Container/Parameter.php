<?php

namespace Zapheus\Container;

/**
 * @package Zapheus
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Parameter
{
    /**
     * @var \ReflectionParameter
     */
    protected $self;

    /**
     * @param \ReflectionParameter $self
     */
    public function __construct(\ReflectionParameter $self)
    {
        $this->self = $self;
    }

    /**
     * Returns a \ReflectionClass object for the
     * parameter being reflected or "null".
     *
     * @return \ReflectionClass<object>|null
     *
     * @codeCoverageIgnore
     */
    public function getClass()
    {
        $php8 = version_compare(PHP_VERSION, '8.0.0', '>=');

        if (! $php8)
        {
            return call_user_func(array($this->self, 'getClass'));
        }

        $type = call_user_func(array($this->self, 'getType'));

        $built = true;

        if ($type)
        {
            $built = call_user_func(array($type, 'isBuiltin'));
        }

        if ($built)
        {
            return null;
        }

        /** @var callable */
        $class = array($type, 'getName');

        /** @var class-string */
        $fn = call_user_func($class);

        return new \ReflectionClass($fn);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = $this->self->getName();

        $class = $this->getClass();

        return $class ? $class->getName() : $name;
    }
}
