<?php

namespace LiteApi\TestPack\Command;

use Exception;
use LiteApi\Command\Input\Argument;
use LiteApi\Command\Input\InputInterface;
use LiteApi\Command\Input\Option;
use LiteApi\Exception\ProgrammerException;
use LiteApi\Exception\Trigger;

class MockInput implements InputInterface
{

    public function __construct(
        private readonly array $input
    )
    {
    }

    /**
     * @var Option[]
     */
    public array $options = [];
    /**
     * @var Argument[]
     */
    public array $arguments = [];

    public function load(): void
    {
        foreach ($this->input as $name => $value) {
            if (is_int($name)) {
                if (isset($this->arguments[$name]) === false) {
                    throw new ProgrammerException(sprintf('Cannot get %s argument', $name));
                }
                $argument = $this->arguments[$name];
                $argument->setValue($value);
            } else {
                $argument = $this->findArgument($name);
                if ($argument !== null) {
                    $argument->setValue($value);
                    break;
                }
                $option = $this->findOption($name);
                if ($option !== null) {
                    $option->setValue($value);
                    break;
                }
                Trigger::warn('Undefined option passed with ' . $name);
            }
        }
    }

    /**
     * @param string $name
     * @return Argument|null
     */
    private function findArgument(string $name): ?Argument
    {
        foreach ($this->arguments as $argument) {
            if ($argument->name === $name) {
                return $argument;
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @return Option|null
     */
    private function findOption(string $name): ?Option
    {
        foreach ($this->options as $option) {
            if ($option->name === $name) {
                return $option;
            }
            if ($option->shortcut === $name) {
                return $option;
            }
        }
        return null;
    }

    public function addOption(
        string $name,
        ?string $shortcut = null,
        int $type = Option::OPTIONAL,
        mixed $default = null,
        string $description = null
    ): void
    {
        $this->options[] = new Option($name, $shortcut, $type, $default, $description);
    }

    public function addArgument(
        string $name,
        int $type = Argument::REQUIRED,
        string $description = null
    ): void
    {
        $this->arguments[] = new Argument($name, $type, $description);
    }

    /**
     * @param string $name
     * @return int|string|null
     * @throws Exception
     */
    public function getOption(string $name): int|string|null
    {
        foreach ($this->options as $option) {
            if ($option->name === $name) {
                return $option->value ?? $option->default;
            }
        }
        throw new Exception("Option $name wasn't found");
    }

    /**
     * @param string $name
     * @return int|string|null
     * @throws Exception
     */
    public function getArgument(string $name): int|string|null
    {
        foreach ($this->arguments as $argument) {
            if ($argument->name === $name) {
                return $argument->value;
            }
        }
        throw new Exception("Argument $name wasn't found");
    }
}