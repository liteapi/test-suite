<?php

namespace LiteApi\TestPack\Constraint;

use LiteApi\Http\Response;
use PHPUnit\Framework\Constraint\Constraint;

class ResponseHeaderSame extends Constraint
{

    public function __construct(
        private readonly string $headerName,
        private readonly string $expectedValue,
    )
    {
    }

    public function toString(): string
    {
        return 'header name ' . $this->headerName . ' with value ' . $this->expectedValue;
    }

    /**
     * @param Response $response
     */
    protected function matches($response): bool
    {
        return $response->headers->get($this->headerName) === $this->expectedValue;
    }
}