<?php

declare(strict_types=1);

namespace ObjectMother\ConstructorBuilder;

abstract class ClassInstance implements ParamResolver
{
    abstract protected function getAllowedClass(): string;
    abstract protected function resolveClass(
        \ReflectionClass $class,
        bool $isOptional,
        bool $isVariadic
    ): object;

    final public function canResolve(\ReflectionParameter $parameter): bool
    {
        if ($parameter->hasType() && $parameter->getClass() !== null) {
            $instance = $parameter
                ->getClass()
                ->newInstanceWithoutConstructor();

            $allowedClassName = $this->getAllowedClass();
            return $instance instanceof $allowedClassName;
        }

        return false;
    }

    final public function resolve(\ReflectionParameter $parameter)
    {
        return $this->resolveClass(
            $parameter->getClass(),
            $parameter->isOptional(),
            $parameter->isVariadic()
        );
    }
}