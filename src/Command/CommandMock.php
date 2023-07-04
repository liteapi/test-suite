<?php

namespace LiteApi\TestPack\Command;

use LiteApi\Kernel;

class CommandMock
{

    private int $resultCode;
    private string $output;
    private array $rawInput;

    public function __construct(
        private readonly Kernel $kernel,
        private readonly string $name
    )
    {
    }

    public function execute(array $rawInput): void
    {
        $this->rawInput = $rawInput;
        $input = new MockInput($this->rawInput);
        $output = new SavedOutput();
        $this->resultCode = $this->kernel->getCommandHandler()
            ->runCommandFromName(
                $this->name,
                $this->kernel->getContainer(),
                $input,
                $output
            );
        $this->output = $output->getText();
    }

    public function getResultCode(): int
    {
        return $this->resultCode;
    }

    public function getRawInput(): array
    {
        return $this->rawInput;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function getName(): string
    {
        return $this->name;
    }

}