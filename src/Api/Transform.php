<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Transform
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all transform rules.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/transforms');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new transform rule.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/transforms');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update a transform rule.
     *
     * @throws ApiException
     */
    public function update(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/api/v1/transforms/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete a transform rule.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/api/v1/transforms/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Test a transform.
     *
     * @throws ApiException
     */
    public function test(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/transforms/test');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
