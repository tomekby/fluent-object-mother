<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

final class OptionalArguments
{
    use TestHelper;

    public function __construct(?string $x, int $default = 12)
    {
        $this->setState(func_get_args());
    }
}