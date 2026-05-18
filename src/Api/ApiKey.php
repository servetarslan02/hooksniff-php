<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class ApiKey
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all API keys.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/api-keys');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new API key.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/api-keys');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete an API key.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/api-keys/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Rotate an API key.
     *
     * @throws ApiException
     */
    public function rotate(string $id): array
    {
        $request = $this->client->newReq('POST', "/v1/api-keys/{$id}/rotate");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
