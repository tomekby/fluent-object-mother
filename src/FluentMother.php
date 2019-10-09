<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace ObjectMother;

//todo: allow building based on a prototype (?)
//todo: resolver checking if there's mother for selected object (?)
abstract class FluentMother
{
    /** @var BuildStrategy */
    private $_buildStrategy;

    final public function __construct()
    {
        $this->_buildStrategy = $this->_initialize();
    }

    abstract protected function _initialize(): BuildStrategy;

    final public function __set(string $name, $value): void
    {
        $this->_buildStrategy->set($name, $value);
    }

    final public function __unset(string $name): void
    {
        $this->_buildStrategy->unset($name);
    }

    final public function __call(string $name, array $arguments): FluentMother
    {
        $this->_buildStrategy->set($name, ...$arguments);
        return $this;
    }

    final public function build(): object
    {
        return $this->_buildStrategy->build();
    }
}