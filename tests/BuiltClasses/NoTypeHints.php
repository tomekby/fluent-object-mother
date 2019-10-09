<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

final class NoTypeHints
{
    public function __construct($x, $y, ...$z)
    {

    }
}