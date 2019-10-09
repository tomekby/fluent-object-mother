<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

final class FewArguments
{
    use TestHelper;

    public function __construct(int $a, string $b, bool $c, float $d)
    {
        $this->setState(func_get_args());
    }
}