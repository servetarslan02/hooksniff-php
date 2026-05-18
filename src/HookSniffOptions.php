<?php

declare(strict_types=1);

namespace HookSniff;

class HookSniffOptions
{
    public function __construct(
        public bool $debug = false,
        public ?string $serverUrl = null,
        public ?int $timeoutMs = 30000,
        public ?int $numRetries = 2,
        public ?array $retryScheduleMs = [60, 120, 240],
        /** Custom headers to include in every request */
        public array $headers = [],
    ) {}

    public static function newDefault(string $token): HookSniffOptions
    {
        return new HookSniffOptions(
            debug: false,
            serverUrl: Utils::getServerUrlFromToken($token),
            timeoutMs: 30000,
            numRetries: 2,
            retryScheduleMs: [60, 120, 240],
            headers: [],
        );
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }
}
