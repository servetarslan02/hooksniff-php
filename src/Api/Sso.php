<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Sso
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Get SSO configuration.
     *
     * @throws ApiException
     */
    public function getConfig(): array
    {
        $request = $this->client->newReq('GET', '/v1/sso/config');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create or update SSO configuration.
     *
     * @throws ApiException
     */
    public function upsertConfig(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/sso/config');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete SSO configuration.
     *
     * @throws ApiException
     */
    public function deleteConfig(): void
    {
        $request = $this->client->newReq('DELETE', '/v1/sso/config');
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Test SSO connection.
     *
     * @throws ApiException
     */
    public function testConnection(): array
    {
        $request = $this->client->newReq('POST', '/v1/sso/test');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
