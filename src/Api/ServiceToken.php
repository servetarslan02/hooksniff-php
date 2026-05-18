<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class ServiceToken
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all service tokens.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/service-tokens');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new service token.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/service-tokens');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete a service token.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/service-tokens/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Update a service token.
     *
     * @throws ApiException
     */
    public function update(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/service-tokens/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Reveal a service token's secret.
     *
     * @throws ApiException
     */
    public function reveal(string $id): array
    {
        $request = $this->client->newReq('POST', "/v1/service-tokens/{$id}/reveal");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
