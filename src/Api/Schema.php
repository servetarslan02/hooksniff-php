<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Schema
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all schemas.
     *
     * @throws ApiException
     */
    public function list(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/schemas');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Register a new schema.
     *
     * @throws ApiException
     */
    public function register(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/schemas');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get a schema by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/api/v1/schemas/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Validate an event against a schema.
     *
     * @throws ApiException
     */
    public function validate(string $id, array $body): array
    {
        $request = $this->client->newReq('POST', "/api/v1/schemas/{$id}/validate");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
