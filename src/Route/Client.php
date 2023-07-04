<?php

namespace LiteApi\TestPack\Route;

use Exception;
use LiteApi\Http\Request;
use LiteApi\Http\Response;
use LiteApi\Kernel;
use LiteApi\TestPack\Route\History\HistoryElement;
use LiteApi\TestPack\Route\History\HistoryStack;

class Client
{

    protected const IDENTIFIER_HEADER = '___CLIENT_ID___';
    protected Request $request;
    protected Response $response;
    protected HistoryStack $history;

    public function __construct(
        private readonly Kernel $kernel,
        private array           $server = [],
        private array           $headers = []
    )
    {
        $this->history = new HistoryStack();
        if (isset($this->headers[self::IDENTIFIER_HEADER])) {
            $this->headers[self::IDENTIFIER_HEADER] = uniqid();
        }
        if (isset($this->server['REMOTE_ADDR'])) {
            $this->server['REMOTE_ADDR'] = '127.0.0.1';
        }
    }

    public function sendRequest(
        string $url,
        string $method,
        ?string $content = null,
        array $request = [],
        array $files = [],
        array $server = []
    ): Response
    {
        if (isset($this->request) && isset($this->response)) {
            $this->history->add(new HistoryElement($this->request, $this->response));
        }
        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path) && $path !== '') {
            throw new Exception('Path must not empty string');
        }
        $queryStr = parse_url($url, PHP_URL_QUERY);
        if (is_string($queryStr)) {
            parse_str($queryStr, $query);
        } else {
            $query = [];
        }
        $server = array_merge($this->server, $server, [
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $path
        ]);
        $this->request = new Request($query, $request, [], $files, $server, [], $content);
        $this->response = $this->kernel->handleRequest($this->request);
        return $this->response;
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