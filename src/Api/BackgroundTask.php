<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class BackgroundTask
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all background tasks.
     *
     * @throws ApiException
     */
    public function list(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/v1/background-tasks');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get a background task by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/background-tasks/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Cancel a background task.
     *
     * @throws ApiException
     */
    public function cancel(string $id): array
    {
        $request = $this->client->newReq('PUT', "/v1/background-tasks/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
