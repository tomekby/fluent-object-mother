<?php

declare(strict_types=1);

namespace Unit\ObjectMother\Functional;

use ObjectMother\Constructor;
use ObjectMother\FluentMother;
use PHPUnit\Framework\TestCase;
use Tests\ObjectMother\BuiltClasses\{
    BaseValueObjects,
    EmptyConstructor,
    FewArguments,
    NoConstructor,
    NoTypeHints,
    OptionalArguments,
    Variadic
};

// We have to require it manually, as it is not PSR-4 compliant
require_once __DIR__ . '/../BuiltClasses/GlobalObject.php';

final class ConstructorBasedMotherTest extends TestCase
{
    /** @var string Class to be built */
    public static $builtClass;

    private function createMotherFor(string $className): FluentMother
    {
        self::$builtClass = $className;
        return new class extends FluentMother {
            use Constructor;

            protected function _class(): string
            {
                return ConstructorBasedMotherTest::$builtClass;
            }
        };
    }

    /**
     * @test
     * @dataProvider shouldBuildWithDefaultsDataProvider
     * @param string $class
     */
    public function shouldBuildWithDefaults(string $class): void
    {
        $result = $this->createMotherFor($class)->build();

        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf($class, $result);
    }

    public function shouldBuildWithDefaultsDataProvider(): array
    {
        $classes = [
            BaseValueObjects::class,
            EmptyConstructor::class,
            FewArguments::class,
            \GlobalObject::class,
            NoConstructor::class,
            NoTypeHints::class,
            OptionalArguments::class,
            Variadic::class,
        ];

        // Use class name as case name
        return array_map(static function (string $className) {
            return [$className];
        }, array_combine($classes, $classes));
    }

    /**
     * @test
     */
    public function shouldSetUnset(): void
    {
        $mother = $this->createMotherFor(FewArguments::class);

        // Set params through magic call & magic setter
        $mother->a(123);
        $mother->b = '123';

        // check if we got expected data in constructor
        $expected = ['a' => 123, 'b' => '123', 'c' => true, 'd' => 0.0];
        self::assertSame($expected, $mother->build()->getState());

        // Unset b field -> should be initiated based on type hint
        unset($mother->b);

        $expected['b'] = '';
        self::assertSame($expected, $mother->build()->getState());
    }

    /**
     * @test
     */
    public function shouldDefineFluentDefault(): void
    {
        $mother = new class extends FluentMother {
            use Constructor;

            protected function _class(): string
            {
                return OptionalArguments::class;
            }

            // Method building some predefined object
            public function defaultObject(): OptionalArguments
            {
                return (new self)
                    ->x('some value')
                    ->default(0xDEADBEEF)
                    ->build();
            }

            // Similar to above, but returning Mother/builder
            // so we can process it further before constructing
            public function defaultBuilder(): FluentMother
            {
                return (new self)->x('some value');
            }
        };

        self::assertSame(
            ['x' => 'some value', 'default' => 0xDEADBEEF],
            $mother->defaultObject()->getState()
        );
        self::assertSame(
            ['x' => 'some value', 'default' => 12],
            $mother->defaultBuilder()->build()->getState()
        );
    }
}