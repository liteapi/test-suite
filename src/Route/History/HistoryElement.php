<?php

namespace LiteApi\TestPack\Route\History;

use LiteApi\Http\Request;
use LiteApi\Http\Response;

class HistoryElement
{

    public function __construct(
        private readonly Request $request,
        private readonly Response $response
    )
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

}