<?php

namespace LiteApi\TestPack\Command;

use LiteApi\Command\Output\OutputInterface;

class SavedOutput implements OutputInterface
{

    private string $text = '';


    public function write(string $text): void
    {
        $this->text .= $text;
    }

    /**
     * @inheritDoc
     */
    public function writeln(array|string $text): void
    {
        if (is_array($text)) {
            $this->text .= implode(PHP_EOL, $text) . PHP_EOL;
        } else {
            $this->text .= $text . PHP_EOL;
        }
    }

    public function getText(): string
    {
        return $this->text;
    }
}