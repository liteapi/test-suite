<?php

namespace LiteApi\TestPack\Route;

use LiteApi\Event\KernelEvent;
use LiteApi\Event\KernelEventSubscriberInterface;

class TestKernelEventSubscriber implements KernelEventSubscriberInterface
{

    public static function getEventsDefinitions(): array
    {
        return [
            KernelEvent::RequestException->value => [
                'requestException'
            ]
        ];
    }

    public function requestException(array $requestException): void
    {
        $exception = $requestException[0];
        $response = $requestException[1];
        //TODO:
    }


}