<?php

namespace LiteApi\TestPack\Constraint;

use LiteApi\Http\Response;
use LiteApi\Http\ResponseStatus;
use PHPUnit\Framework\Constraint\Constraint;

class ResponseStatusCodeTheSame extends Constraint
{

    public function __construct(
        private ResponseStatus $status
    )
    {
    }

    public function toString(): string
    {
        return 'status code is ' . $this->status->value;
    }

    /**
     * @param Response $response
     */
    protected function matches($response): bool
    {
        return $this->status == $response->status;
    }
}