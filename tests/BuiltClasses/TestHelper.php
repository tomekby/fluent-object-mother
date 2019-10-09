<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

trait TestHelper
{
    protected $state;

    // Map argument values to their names
    protected function setState(array $args): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->state = array_combine(
            array_map(static function (\ReflectionParameter $parameter) {
                return $parameter->getName();
            }, (new \ReflectionClass($this))->getConstructor()->getParameters()),
            $args
        );
    }

    public function getState(): array
    {
        return $this->state;
    }
}