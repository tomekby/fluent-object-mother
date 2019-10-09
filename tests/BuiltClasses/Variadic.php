<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

final class Variadic
{
    public function __construct(string ...$variadic)
    {
    }
}