<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Alert
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all alerts.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/alerts');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new alert.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/alerts');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get an alert by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/api/v1/alerts/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update an alert.
     *
     * @throws ApiException
     */
    public function update(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/api/v1/alerts/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete an alert.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/api/v1/alerts/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Test an alert.
     *
     * @throws ApiException
     */
    public function test(string $id): array
    {
        $request = $this->client->newReq('POST', "/api/v1/alerts/{$id}/test");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
