<?php

namespace LiteApi\TestPack\Route\History;

use Iterator;

class HistoryStack implements Iterator
{

    /** @var HistoryElement[] */
    private array $elements = [];
    private int $position = 0;

    public function add(HistoryElement $element): void
    {
        $this->elements[] = $element;
    }

    public function current(): HistoryElement
    {
        return $this->elements[$this->position];
    }

    public function next(): void
    {
        $this->position += 1;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->elements[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}