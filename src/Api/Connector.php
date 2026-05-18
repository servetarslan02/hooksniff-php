<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Connector
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all connectors.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/connectors');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get a connector by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/connectors/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * List all connector configs.
     *
     * @throws ApiException
     */
    public function listConfigs(): array
    {
        $request = $this->client->newReq('GET', '/v1/connectors/configs');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new connector config.
     *
     * @throws ApiException
     */
    public function createConfig(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/connectors/configs');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get a connector config by ID.
     *
     * @throws ApiException
     */
    public function getConfig(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/connectors/configs/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update a connector config.
     *
     * @throws ApiException
     */
    public function updateConfig(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/connectors/configs/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete a connector config.
     *
     * @throws ApiException
     */
    public function deleteConfig(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/connectors/configs/{$id}");
        $this->client->sendNoResponseBody($request);
    }
}
