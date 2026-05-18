<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class RateLimit
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all rate limits.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/rate-limits');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get rate limit for an endpoint.
     *
     * @throws ApiException
     */
    public function get(string $endpointId): array
    {
        $request = $this->client->newReq('GET', "/v1/rate-limits/{$endpointId}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Set rate limit for an endpoint.
     *
     * @throws ApiException
     */
    public function set(string $endpointId, array $body): array
    {
        $request = $this->client->newReq('POST', "/v1/rate-limits/{$endpointId}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete rate limit for an endpoint.
     *
     * @throws ApiException
     */
    public function delete(string $endpointId): void
    {
        $request = $this->client->newReq('DELETE', "/v1/rate-limits/{$endpointId}");
        $this->client->sendNoResponseBody($request);
    }
}
