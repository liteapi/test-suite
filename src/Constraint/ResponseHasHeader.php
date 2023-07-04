<?php

namespace LiteApi\TestPack\Constraint;

use LiteApi\Http\Response;
use PHPUnit\Framework\Constraint\Constraint;

class ResponseHasHeader extends Constraint
{

    public function __construct(
        private readonly string $headerName
    )
    {
    }

    public function toString(): string
    {
        return 'header name ' . $this->headerName;
    }

    /**
     * @param Response $response
     */
    protected function matches($response): bool
    {
        return $response->headers->get($this->headerName) !== null;
    }
}