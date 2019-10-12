<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

final class PrivateConstructor
{
    private function __construct(int $x, string $y)
    {
    }
}