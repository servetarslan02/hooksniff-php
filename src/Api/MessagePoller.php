<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class MessagePoller
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Poll for new messages.
     *
     * @throws ApiException
     */
    public function poll(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/api/v1/message-poller/poll');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Seek the message cursor to a specific position.
     *
     * @throws ApiException
     */
    public function seek(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/message-poller/seek');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Commit the message cursor (mark messages as processed).
     *
     * @throws ApiException
     */
    public function commit(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/message-poller/commit');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
