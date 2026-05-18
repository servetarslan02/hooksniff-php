<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Application
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all applications.
     *
     * @throws ApiException
     */
    public function list(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/applications');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new application.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/applications');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get an application by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/api/v1/applications/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update an application.
     *
     * @throws ApiException
     */
    public function update(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/api/v1/applications/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete an application.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/api/v1/applications/{$id}");
        $this->client->sendNoResponseBody($request);
    }
}
