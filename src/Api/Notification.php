<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Notification
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all notifications.
     *
     * @throws ApiException
     */
    public function list(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/v1/notifications');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get unread notification count.
     *
     * @throws ApiException
     */
    public function unreadCount(): array
    {
        $request = $this->client->newReq('GET', '/v1/notifications/unread-count');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Mark all notifications as read.
     *
     * @throws ApiException
     */
    public function markAllRead(): void
    {
        $request = $this->client->newReq('PUT', '/v1/notifications/read-all');
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Mark a notification as read.
     *
     * @throws ApiException
     */
    public function markRead(string $id): void
    {
        $request = $this->client->newReq('PUT', "/v1/notifications/{$id}/read");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Delete a notification.
     *
     * @throws ApiException
     */
    public function delete(string $id): void
    {
        $request = $this->client->newReq('DELETE', "/v1/notifications/{$id}");
        $this->client->sendNoResponseBody($request);
    }
}
