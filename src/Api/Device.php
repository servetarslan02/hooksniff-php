<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Device
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all devices.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/v1/devices');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Register a new device.
     *
     * @throws ApiException
     */
    public function register(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/devices');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete a device.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/devices/{$id}");
        $this->client->sendNoResponseBody($request);
    }
}
