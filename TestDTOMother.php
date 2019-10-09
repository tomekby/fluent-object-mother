<?php

declare(strict_types=1);

use ObjectMother\Constructor;
use ObjectMother\FluentMother;

/**
 * @method TestDTO build()
 *
 * @property int $foo
 * @property string $baz
 * @property string[] $bar
 * @method TestDTOMother foo(int $_)
 * @method TestDTOMother bar(string[] $_)
 * @method TestDTOMother baz(string $_)
 * @method TestDTOMother date(DateTimeImmutable $_)
 */
final class TestDTOMother extends FluentMother
{
    use Constructor;

    protected function _class(): string
    {
        return TestDTO::class;
    }

    public static function predefined(): self
    {
        return (new self)
            ->foo(123)
            ->baz('pre-initialized value');
    }
}