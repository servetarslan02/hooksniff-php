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
        $request = $this->client->newReq('GET', '/v1/stream/channels');
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
        $request = $this->client->newReq('GET', "/v1/stream/channels/{$id}");
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
        $request = $this->client->newReq('POST', '/v1/stream/channels');
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
        $request = $this->client->newReq('PUT', "/v1/stream/channels/{$id}");
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
        $request = $this->client->newReq('DELETE', "/v1/stream/channels/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * List messages in a stream channel.
     *
     * @throws ApiException
     */
    public function listMessages(string $id, array $params = []): array
    {
        $request = $this->client->newReq('GET', "/v1/stream/channels/{$id}/messages");
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
        $request = $this->client->newReq('GET', '/v1/stream/subscriptions');
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
        $request = $this->client->newReq('DELETE', "/v1/stream/subscriptions/{$id}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Publish a message to a stream channel.
     *
     * @throws ApiException
     */
    public function publish(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/stream/publish');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}

    /**
     * Subscribe to real-time events via SSE on a channel.
     *
     * @param string $channelId Channel ID to subscribe to
     * @param callable $onEvent Callback for each event (receives array)
     * @return void
     * @throws ApiException
     */
    public function subscribe(string $channelId, callable $onEvent): void
    {
        $request = $this->client->newReq('GET', "/v1/stream/channels/{$channelId}/subscribe");
        $request->setHeader('Accept', 'text/event-stream');

        $url = $request->getUri();
        $headers = $this->client->getHeaders();

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array_map(fn($k, $v) => "$k: $v", array_keys($headers), $headers),
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use ($onEvent) {
                $lines = explode("\n", $chunk);
                $eventType = '';
                $data = '';
                foreach ($lines as $line) {
                    if (str_starts_with($line, 'event:')) {
                        $eventType = trim(substr($line, 6));
                    } elseif (str_starts_with($line, 'data:')) {
                        $data = trim(substr($line, 5));
                    } elseif (empty(trim($line)) && !empty($data)) {
                        $onEvent(['event' => $eventType, 'data' => json_decode($data, true) ?? $data]);
                        $eventType = '';
                        $data = '';
                    }
                }
                return strlen($chunk);
            },
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 0, // SSE is long-lived
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
