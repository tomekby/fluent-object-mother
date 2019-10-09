<?php

declare(strict_types=1);

namespace ObjectMother;

//todo: prototype based strategy
//todo: annotation based strategy
interface BuildStrategy
{
    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws \BadMethodCallException
     * @throws \TypeError
     */
    public function set(string $name, ...$value): void;
    /**
     * @param string $name
     * @throws \BadMethodCallException
     */
    public function unset(string $name): void;
    /**
     * @return object
     * @throws \BadMethodCallException
     * @throws \TypeError
     */
    public function build(): object;
    public function getCoveredClass(): string;
}