<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    private string $body;
    private int $statusCode;
    private array $headers;

    public function __construct(string $body = '', int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public static function html(string $body, int $statusCode = 200): self
    {
        return new self($body, $statusCode, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function json(mixed $data, int $statusCode = 200): self
    {
        return new self(
            json_encode($data, JSON_UNESCAPED_UNICODE),
            $statusCode,
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    public static function redirect(string $url, int $statusCode = 302): self
    {
        return new self('', $statusCode, ['Location' => $url]);
    }

    public static function download(string $content, string $filename, string $mimeType = 'application/octet-stream'): self
    {
        return new self($content, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($content),
        ]);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->body;
    }
}
