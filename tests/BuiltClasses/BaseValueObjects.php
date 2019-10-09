<?php

declare(strict_types=1);

namespace Tests\ObjectMother\BuiltClasses;

final class BaseValueObjects
{
    public function __construct(
        \DateTimeInterface $a,
        \DateTimeImmutable $b,
        \DateTime $c,
        \DateTimeZone $d
    ) {
    }
}