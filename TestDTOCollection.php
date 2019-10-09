<?php

declare(strict_types=1);

final class TestDTOCollection
{
    /**
     * @var TestDTO[]
     */
    private $items;

    public function __construct(TestDTO ...$items)
    {
        $this->items = $items;
    }

    public function dump(): void
    {
        echo "-----\nCollection items: ";
        foreach ($this->items as $dto) {
            $dto->dump();
        }
    }
}