<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Integration
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all integrations.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/integrations');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get an integration by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/integrations/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new integration.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/integrations');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update an integration.
     *
     * @throws ApiException
     */
    public function update(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/integrations/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete an integration.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/integrations/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Test an integration.
     *
     * @throws ApiException
     */
    public function test(string $id): array
    {
        $request = $this->client->newReq('POST', "/v1/integrations/{$id}/test");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * List events for an integration.
     *
     * @throws ApiException
     */
    public function listEvents(string $id, array $params = []): array
    {
        $request = $this->client->newReq('GET', "/v1/integrations/{$id}/events");
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get stats for an integration.
     *
     * @throws ApiException
     */
    public function getStats(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/integrations/{$id}/stats");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
