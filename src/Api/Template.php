<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Template
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all templates.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/templates');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get a template by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/api/v1/templates/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Apply a template.
     *
     * @throws ApiException
     */
    public function apply(string $id, array $body = []): array
    {
        $request = $this->client->newReq('POST', "/api/v1/templates/{$id}/apply");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
