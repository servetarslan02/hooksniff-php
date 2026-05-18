<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class AuditLog
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List audit log entries.
     *
     * @throws ApiException
     */
    public function list(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/audit-log');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get an audit log entry by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/api/v1/audit-log/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
