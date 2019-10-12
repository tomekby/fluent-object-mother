<?php

declare(strict_types=1);

use ObjectMother\Constructor;
use ObjectMother\FluentMother;

/**
 * @method TestDTOCollection build()
 * @method CollectionMother items(TestDTO ...$_)
 */
final class CollectionMother extends FluentMother
{
    use Constructor;

    protected function _class(): string
    {
        return TestDTOCollection::class;
    }

    public static function withElements(): TestDTOCollection
    {
        return (new self)
            ->items(
                TestDTOMother::predefined()->build(),
                (new TestDTOMother)
                    ->date(new DateTimeImmutable('01-01-2019'))
                    ->build()
            )
            ->build();
    }
}