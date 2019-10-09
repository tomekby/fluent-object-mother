<?php

declare(strict_types=1);

namespace ObjectMother\ConstructorBuilder;

final class Builtin implements ParamResolver
{
    public function canResolve(\ReflectionParameter $parameter): bool
    {
        return $parameter->getType() !== null
            && $parameter->getType()->isBuiltin();
    }

    public function resolve(\ReflectionParameter $parameter)
    {
        switch ($parameter->getType()->getName()) {
            case 'string':
                return '';
            case 'bool':
                return true;
            case 'float':
                return 0.0;
            case 'int':
                return 0;
            case 'array':
            case 'iterable':
                return [];
        }
        throw new \BadMethodCallException('unknown type!');
    }
}