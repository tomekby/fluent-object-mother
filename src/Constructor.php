<?php

declare(strict_types=1);

namespace ObjectMother;

trait Constructor
{
    protected function _initialize(): BuildStrategy
    {
        try {
            $strategy = new ConstructorBuilder($this->_class());
        } catch (\ReflectionException $e) {
            throw new \RuntimeException($e->getMessage());
        }
        // Initialize strategy with optional default values
        foreach ($this->_defaults() as $name => $value) {
            $strategy->set($name, $value);
        }

        return $strategy;
    }

    abstract protected function _class(): string;

    protected function _defaults(): array
    {
        return [];
    }
}