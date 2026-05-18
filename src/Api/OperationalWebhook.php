<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class OperationalWebhook
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all operational webhook endpoints.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/operational-webhooks');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new operational webhook endpoint.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/operational-webhooks');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get an operational webhook endpoint by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/v1/operational-webhooks/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update an operational webhook endpoint.
     *
     * @throws ApiException
     */
    public function update(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/operational-webhooks/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete an operational webhook endpoint.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/operational-webhooks/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * List deliveries for an operational webhook endpoint.
     *
     * @throws ApiException
     */
    public function listDeliveries(string $id, array $params = []): array
    {
        $request = $this->client->newReq('GET', "/v1/operational-webhooks/{$id}/deliveries");
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
