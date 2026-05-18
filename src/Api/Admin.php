<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Admin
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all users (admin).
     *
     * @throws ApiException
     */
    public function listUsers(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/v1/admin/users');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Export users as CSV (admin).
     *
     * @throws ApiException
     */
    public function exportUsersCsv(): string
    {
        $request = $this->client->newReq('GET', '/v1/admin/users/export');
        $res = $this->client->send($request);

        return $res;
    }

    /**
     * Get user detail (admin).
     *
     * @throws ApiException
     */
    public function getUserDetail(string $userId): array
    {
        $request = $this->client->newReq('GET', "/v1/admin/users/{$userId}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Change user plan (admin).
     *
     * @throws ApiException
     */
    public function changePlan(string $userId, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/admin/users/{$userId}/plan");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get user plan history (admin).
     *
     * @throws ApiException
     */
    public function getUserPlanHistory(string $userId): array
    {
        $request = $this->client->newReq('GET', "/v1/admin/users/{$userId}/plan-history");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Send email to user (admin).
     *
     * @throws ApiException
     */
    public function sendUserEmail(string $userId, array $body): array
    {
        $request = $this->client->newReq('POST', "/v1/admin/users/{$userId}/send-email");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Change user status (admin).
     *
     * @throws ApiException
     */
    public function changeStatus(string $userId, array $body): array
    {
        $request = $this->client->newReq('PUT', "/v1/admin/users/{$userId}/status");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Impersonate a user (admin).
     *
     * @throws ApiException
     */
    public function impersonateUser(string $userId): array
    {
        $request = $this->client->newReq('POST', "/v1/admin/users/{$userId}/impersonate");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get user analytics (admin).
     *
     * @throws ApiException
     */
    public function getUserAnalytics(string $userId): array
    {
        $request = $this->client->newReq('GET', "/v1/admin/users/{$userId}/analytics");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get system stats (admin).
     *
     * @throws ApiException
     */
    public function getSystemStats(): array
    {
        $request = $this->client->newReq('GET', '/v1/admin/stats');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get revenue by month (admin).
     *
     * @throws ApiException
     */
    public function getRevenue(): array
    {
        $request = $this->client->newReq('GET', '/v1/admin/revenue');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Export revenue as CSV (admin).
     *
     * @throws ApiException
     */
    public function exportRevenueCsv(): string
    {
        $request = $this->client->newReq('GET', '/v1/admin/revenue/export');
        $res = $this->client->send($request);

        return $res;
    }

    /**
     * Get churn report (admin).
     *
     * @throws ApiException
     */
    public function getChurnReport(): array
    {
        $request = $this->client->newReq('GET', '/v1/admin/churn');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get audit logs (admin).
     *
     * @throws ApiException
     */
    public function getAuditLogs(array $params = []): array
    {
        $request = $this->client->newReq('GET', '/v1/admin/audit-logs');
        foreach ($params as $key => $value) {
            $request->setQueryParam($key, $value);
        }
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Replay a delivery (admin).
     *
     * @throws ApiException
     */
    public function replayDelivery(string $deliveryId): array
    {
        $request = $this->client->newReq('POST', "/v1/admin/deliveries/{$deliveryId}/replay");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Send test webhook (admin).
     *
     * @throws ApiException
     */
    public function testWebhook(array $body = []): array
    {
        $request = $this->client->newReq('POST', '/v1/admin/test-webhook');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get platform settings (admin).
     *
     * @throws ApiException
     */
    public function getSettings(): array
    {
        $request = $this->client->newReq('GET', '/v1/admin/settings');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update platform settings (admin).
     *
     * @throws ApiException
     */
    public function updateSettings(array $body): array
    {
        $request = $this->client->newReq('PUT', '/v1/admin/settings');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
