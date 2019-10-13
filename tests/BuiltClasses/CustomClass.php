<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

final class CustomClass
{
    use TestHelper;

    public function __construct(\GlobalObject $class)
    {
        $this->setState(func_get_args());
    }
}