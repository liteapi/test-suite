<?php

namespace LiteApi\TestPack\Constraint;

use LiteApi\Http\Response;
use LiteApi\Http\ResponseStatus;
use PHPUnit\Framework\Constraint\Constraint;

class ResponseSuccessful extends Constraint
{

    public function toString(): string
    {
        return 'response is successful';
    }

    /**
     * @param Response $response
     */
    protected function matches($response): bool
    {
        return ResponseStatus::Ok == $response->status;
    }
}