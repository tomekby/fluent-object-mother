<?php

declare(strict_types=1);

namespace ObjectMother\ConstructorBuilder;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use MyCLabs\Enum\Enum;

/**
 * Resolve base value objects, e.g. DateTime(Immutable) or Enum
 */
final class BaseValueObject implements ParamResolver
{
    private const ALLOWED_TYPES = [
        DateTimeInterface::class,
        DateTimeImmutable::class,
        DateTime::class,
        DateTimeZone::class,
    ];

    public function canResolve(\ReflectionParameter $parameter): bool
    {
        return $parameter->getClass() !== null
            && (
                in_array($parameter->getClass()->getName(), self::ALLOWED_TYPES, true) ||
                $parameter->getClass()->isSubclassOf(Enum::class)
            );
    }

    public function resolve(\ReflectionParameter $parameter)
    {
        $class = $parameter->getClass();
        switch ($class->getName()) {
            case DateTimeImmutable::class:
            case DateTimeInterface::class:
                return (new DateTimeImmutable)->setTime(0, 0);
            case DateTime::class:
                return (new DateTime)->setTime(0, 0);
            case DateTimeZone::class:
                return new DateTimeZone('UTC');
        }
        // For enum use first defined constant
        if ($class->isSubclassOf(Enum::class)) {
            $constants = $class->getConstants();
            return new $class(reset($constants));
        }

        throw new \BadMethodCallException('unknown type!');
    }
}