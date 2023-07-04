<?php

declare(strict_types=1);

namespace LiteApi\TestPack;

use LiteApi\Http\Request;
use LiteApi\Http\Response;
use LiteApi\Http\ResponseStatus;
use LiteApi\TestPack\Constraint\ResponseHasHeader;
use LiteApi\TestPack\Constraint\ResponseHeaderSame;
use LiteApi\TestPack\Constraint\ResponseStatusCodeTheSame;
use LiteApi\TestPack\Constraint\ResponseSuccessful;
use LiteApi\TestPack\Exception\DebugException;
use LiteApi\TestPack\Route\Client;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;

class WebTestCase extends KernelTestCase
{

    protected static Client $client;

    protected function createClient($headers = []): Client
    {
        if (self::$kernel === null) {
            self::bootKernel();
        }
        self::$client = new Client(self::$kernel, $headers);
        return self::$client;
    }

    public static function getClient(): Client
    {
        return self::$client;
    }

    public static function getRequest(): Request
    {
        return self::$client->getRequest();
    }

    public static function getResponse(): Response
    {
        return self::$client->getResponse();
    }

    public function assertResponseSuccessful(string $message = ''): void
    {
        self::doAssertion(new ResponseSuccessful(), $message);
    }

    public function assertResponseStatusCode(ResponseStatus $status, string $message = ''): void
    {
        self::doAssertion(new ResponseStatusCodeTheSame($status), $message);
    }

    public function assertResponseHasHeader(string $headerName, string $message = ''): void
    {
        self::doAssertion(new ResponseHasHeader($headerName), $message);
    }

    public function assertResponseNotHasHeader(string $headerName, string $message = ''): void
    {
        self::doAssertion(new LogicalNot(new ResponseHasHeader($headerName)), $message);
    }

    public function assertResponseHeaderSame(string $headerName, string $expectedValue, string $message = ''): void
    {
        self::doAssertion(new ResponseHeaderSame($headerName, $expectedValue), $message);
    }

    public function assertResponseHeaderNotSame(string $headerName, string $expectedValue, string $message = ''): void
    {
        self::doAssertion(new LogicalNot(new ResponseHeaderSame($headerName, $expectedValue)), $message);
    }



    private static function doAssertion(Constraint $constraint, string $message): void
    {
        try {
            self::assertThat(self::getRequest(), $constraint, $message);
        } catch (ExpectationFailedException $e) {
            $headers = self::getResponse()->headers;
            $message = $headers->get(DebugException::DEBUG_MESSAGE);
            $stackTrace = $headers->get(DebugException::DEBUG_STACK_TRACE);
            if ($message !== null && $stackTrace !== null) {
                $error = new DebugException($message, $stackTrace);
                $e = new ExpectationFailedException($e->getMessage(), $e->getComparisonFailure(), $error);
            }
            throw $e;
        }
    }

}