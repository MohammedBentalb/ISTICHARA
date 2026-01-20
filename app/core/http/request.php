<?php

namespace App\Core\Http;

class Request
{
    private array $jsonBody = [];

    public function __construct()
    {
        // Decode JSON once at creation
        $raw = file_get_contents("php://input");
        $decoded = json_decode($raw, true);
        $this->jsonBody = is_array($decoded) ? $decoded : [];
    }

    public function getRequestType(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getClearPath(): string
    {
        return trim(parse_url($this->getPath(), PHP_URL_PATH), "/");
    }

    public function getQuery(string $query): ?string
    {
        return $_GET[$query] ?? null;
    }

    public function getParam(string $param): mixed
    {
        // Priority: POST > JSON body
        if (isset($_POST[$param])) {
            return $_POST[$param];
        }

        return $this->jsonBody[$param] ?? null;
    }

    public function getPreviousPath(): string
    {
        return htmlspecialchars($_SERVER['HTTP_REFERER'] ?? "", ENT_QUOTES, 'UTF-8');
    }

    public function getOrigin(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? "UNKNOWN";
    }
}
