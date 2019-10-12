<?php

declare(strict_types=1);

final class TestDTO
{
    private $data;

    public function __construct(
        int $foo,
        string $baz,
        DateTimeImmutable $date,
        array $bar = ['bar']
    ) {
        $this->data = [
            'foo' => $foo,
            'baz' => $baz,
            'date' => $date,
            'bar' => $bar
        ];
    }

    public function dump(): void
    {
        echo json_encode($this->data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT) . PHP_EOL;
    }
}