<?php

declare(strict_types=1);

namespace ObjectMother\ConstructorBuilder;

interface ParamResolver
{
    public function canResolve(\ReflectionParameter $parameter): bool;
    public function resolve(\ReflectionParameter $parameter);
}