<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class CustomDomain
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all custom domains.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/custom-domains');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Add a custom domain.
     *
     * @throws ApiException
     */
    public function add(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/custom-domains');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete a custom domain.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/api/v1/custom-domains/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Verify a custom domain.
     *
     * @throws ApiException
     */
    public function verify(string $id): array
    {
        $request = $this->client->newReq('POST', "/api/v1/custom-domains/{$id}/verify");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
