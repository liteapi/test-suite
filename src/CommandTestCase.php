<?php

declare(strict_types=1);

namespace LiteApi\TestPack;

use LiteApi\TestPack\Command\CommandMock;

class CommandTestCase extends KernelTestCase
{

    protected static CommandMock $command;

    protected function createCommand(string $name): CommandMock
    {
        if (self::$kernel === null) {
            self::bootKernel();
        }
        self::$command = new CommandMock(self::$kernel, $name);
        return self::$command;
    }

    public static function getCommand(): CommandMock
    {
        return self::$command;
    }

    public static function getInput(): array
    {
        return self::$command->getRawInput();
    }

    public static function getOutput(): string
    {
        return self::$command->getOutput();
    }

    public static function getResultCode(): int
    {
        return self::$command->getResultCode();
    }
    

}