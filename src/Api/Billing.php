<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Billing
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Get current subscription.
     *
     * @throws ApiException
     */
    public function getSubscription(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/billing/subscription');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Cancel current subscription.
     *
     * @throws ApiException
     */
    public function cancelSubscription(): void
    {
        $request = $this->client->newReq('DELETE', '/api/v1/billing/subscription');
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Upgrade plan.
     *
     * @throws ApiException
     */
    public function upgrade(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/billing/upgrade');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Open billing portal.
     *
     * @throws ApiException
     */
    public function openPortal(): array
    {
        $request = $this->client->newReq('POST', '/api/v1/billing/portal');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get billing usage.
     *
     * @throws ApiException
     */
    public function getUsage(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/billing/usage');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get invoices.
     *
     * @throws ApiException
     */
    public function getInvoices(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/billing/invoices');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Request a refund.
     *
     * @throws ApiException
     */
    public function requestRefund(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/billing/refund');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get overage settings.
     *
     * @throws ApiException
     */
    public function getOverageSettings(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/billing/settings');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update overage settings.
     *
     * @throws ApiException
     */
    public function updateOverageSettings(array $body): array
    {
        $request = $this->client->newReq('PUT', '/api/v1/billing/settings');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
