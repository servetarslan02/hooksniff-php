<?php

namespace HookSniff;

/**
 * Response metadata from the last API request.
 *
 * @property-read int $statusCode HTTP status code
 * @property-read string|null $requestId x-request-id header
 * @property-read int|null $rateLimitRemaining x-ratelimit-remaining header
 * @property-read int|null $rateLimitReset x-ratelimit-reset header
 * @property-read array $headers All response headers
 */
class ResponseMetadata
{
    private int $statusCode;
    private ?string $requestId;
    private ?int $rateLimitRemaining;
    private ?int $rateLimitReset;
    private array $headers;

    public function __construct(
        int $statusCode,
        ?string $requestId = null,
        ?int $rateLimitRemaining = null,
        ?int $rateLimitReset = null,
        array $headers = []
    ) {
        $this->statusCode = $statusCode;
        $this->requestId = $requestId;
        $this->rateLimitRemaining = $rateLimitRemaining;
        $this->rateLimitReset = $rateLimitReset;
        $this->headers = $headers;
    }

    public function getStatusCode(): int { return $this->statusCode; }
    public function getRequestId(): ?string { return $this->requestId; }
    public function getRateLimitRemaining(): ?int { return $this->rateLimitRemaining; }
    public function getRateLimitReset(): ?int { return $this->rateLimitReset; }
    public function getHeaders(): array { return $this->headers; }

    public function getHeader(string $name): ?string
    {
        $lower = strtolower($name);
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $lower) {
                return is_array($value) ? $value[0] : $value;
            }
        }
        return null;
    }

    public function __get($name)
    {
        return match ($name) {
            'statusCode' => $this->statusCode,
            'requestId' => $this->requestId,
            'rateLimitRemaining' => $this->rateLimitRemaining,
            'rateLimitReset' => $this->rateLimitReset,
            'headers' => $this->headers,
            default => null,
        };
    }
}
