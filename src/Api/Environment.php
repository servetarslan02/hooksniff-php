<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Environment
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all environments.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/environments');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new environment.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/environments');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get an environment by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/environments/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update an environment.
     *
     * @throws ApiException
     */
    public function update(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/environments/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete an environment.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/environments/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * List variables for an environment.
     *
     * @throws ApiException
     */
    public function listVariables(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/environments/{$id}/variables");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a variable for an environment.
     *
     * @throws ApiException
     */
    public function createVariable(string $id, array $body): array
    {
        $request = $this->client->newReq('POST', "/v1/environments/{$id}/variables");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update a variable for an environment.
     *
     * @throws ApiException
     */
    public function updateVariable(string $envId, string $varId, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/environments/{$envId}/variables/{$varId}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete a variable for an environment.
     *
     * @throws ApiException
     */
    public function deleteVariable(string $envId, string $varId): void
    {
        $request = $this->client->newReq('DELETE', "/v1/environments/{$envId}/variables/{$varId}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Bulk upsert variables for an environment.
     *
     * @throws ApiException
     */
    public function bulkUpsertVariables(string $id, array $body): array
    {
        $request = $this->client->newReq('POST', "/v1/environments/{$id}/variables/bulk");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
