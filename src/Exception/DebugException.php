<?php

namespace LiteApi\TestPack\Exception;

use Exception;

class DebugException extends Exception
{

    public const DEBUG_MESSAGE = '__DEBUG_EXCEPTION_MESSAGE__';
    public const DEBUG_STACK_TRACE = '__DEBUG_EXCEPTION_STACK_TRACE__';

    private array $stackTrace;

    public function __construct(string $message = '', string|array $stackTrace = [])
    {
        parent::__construct($message);
        if (is_string($stackTrace)) {
            $stackTrace = json_decode($stackTrace, true);
            if ($stackTrace === false) {
                throw new Exception('Cannot transform stack trace json string to array');
            }
        }
        $this->stackTrace = $stackTrace;
    }

    public function getStackTrace(): array
    {
        return $this->stackTrace;
    }

}