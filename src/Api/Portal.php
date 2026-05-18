<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Portal
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Get portal configuration.
     *
     * @throws ApiException
     */
    public function getConfig(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/portal/config');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update portal configuration.
     *
     * @throws ApiException
     */
    public function updateConfig(array $body): array
    {
        $request = $this->client->newReq('PUT', '/api/v1/portal/config');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get portal profile.
     *
     * @throws ApiException
     */
    public function getProfile(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/portal/me');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get portal usage.
     *
     * @throws ApiException
     */
    public function getUsage(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/portal/usage');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get embed code.
     *
     * @throws ApiException
     */
    public function getEmbedCode(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/portal/embed-code');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
