<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Search
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Search deliveries.
     *
     * @throws ApiException
     */
    public function search(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/search');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
