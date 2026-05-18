<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Routing
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Get routing config for an endpoint.
     *
     * @throws ApiException
     */
    public function get(string $endpointId): array
    {
        $request = $this->client->newReq('GET', "/v1/routing/{$endpointId}/routing");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update routing config for an endpoint.
     *
     * @throws ApiException
     */
    public function update(string $endpointId, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/routing/{$endpointId}/routing");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get health for an endpoint routing.
     *
     * @throws ApiException
     */
    public function getHealth(string $endpointId): array
    {
        $request = $this->client->newReq('GET', "/v1/routing/{$endpointId}/health");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
