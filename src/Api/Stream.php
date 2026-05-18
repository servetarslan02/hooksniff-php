<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Stream
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all stream channels.
     *
     * @throws ApiException
     */
    public function listChannels(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/stream/channels');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get a stream channel by ID.
     *
     * @throws ApiException
     */
    public function getChannel(string $id): array
    {
        $request = $this->client->newReq('GET', "/api/v1/stream/channels/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new stream channel.
     *
     * @throws ApiException
     */
    public function createChannel(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/stream/channels');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update a stream channel.
     *
     * @throws ApiException
     */
    public function updateChannel(string $id, array $body): array
    {
        $request = $this->client->newReq('PUT', "/api/v1/stream/channels/{$id}");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete a stream channel.
     *
     * @throws ApiException
     */
    public function deleteChannel(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/api/v1/stream/channels/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * List messages in a stream channel.
     *
     * @throws ApiException
     */
    public function listMessages(string $id, array $params = []): array
    {
        $request = $this->client->newReq('GET', "/api/v1/stream/channels/{$id}/messages");
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * List all stream subscriptions.
     *
     * @throws ApiException
     */
    public function listSubscriptions(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/stream/subscriptions');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Disconnect a stream subscription.
     *
     * @throws ApiException
     */
    public function disconnectSubscription(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/api/v1/stream/subscriptions/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Publish a message to a stream channel.
     *
     * @throws ApiException
     */
    public function publish(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/stream/publish');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
