<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Analytics
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Get delivery trend analytics.
     *
     * @throws ApiException
     */
    public function deliveryTrend(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/analytics/deliveries');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get success rate analytics.
     *
     * @throws ApiException
     */
    public function successRate(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/analytics/success-rate');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get latency trend analytics.
     *
     * @throws ApiException
     */
    public function latencyTrend(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/analytics/latency');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
