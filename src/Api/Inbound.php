<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Inbound
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all inbound webhook configs.
     *
     * @throws ApiException
     */
    public function listConfigs(): array
    {
        $request = $this->client->newReq('GET', '/v1/inbound/configs');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new inbound webhook config.
     *
     * @throws ApiException
     */
    public function createConfig(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/inbound/configs');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update an inbound webhook config.
     *
     * @throws ApiException
     */
    public function updateConfig(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/inbound/configs/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete an inbound webhook config.
     *
     * @throws ApiException
     */
    public function deleteConfig(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/inbound/configs/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Handle an inbound webhook from a specific provider.
     *
     * @throws ApiException
     */
    public function handle(string $provider, array $body, array $headers = []): array
    {
        $request = $this->client->newReq('POST', "/v1/inbound/{$provider}");
        $request->setBody(json_encode($body));
        foreach ($headers as $key => $value) {
            $request->setHeaderParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
