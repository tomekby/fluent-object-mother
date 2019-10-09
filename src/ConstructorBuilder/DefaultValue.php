<?php

declare(strict_types=1);

namespace ObjectMother\ConstructorBuilder;

final class DefaultValue implements ParamResolver
{
    public function canResolve(\ReflectionParameter $parameter): bool
    {
        return $parameter->allowsNull()
            || $parameter->isDefaultValueAvailable();
    }

    public function resolve(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        return null;
    }
}