<?php

namespace HookSniff;

use HookSniff\WebhookEvents\EndpointCreatedData;
use HookSniff\WebhookEvents\EndpointUpdatedData;
use HookSniff\WebhookEvents\EndpointDeletedData;
use HookSniff\WebhookEvents\EndpointEnabledData;
use HookSniff\WebhookEvents\EndpointDisabledData;
use HookSniff\WebhookEvents\MessageAttemptExhaustedData;
use HookSniff\WebhookEvents\MessageAttemptFailingData;
use HookSniff\WebhookEvents\MessageAttemptRecoveredData;
use HookSniff\WebhookEvents\LastAttemptInfo;
use HookSniff\WebhookEvents\AttemptInfo;

/**
 * Represents a parsed webhook event from HookSniff.
 *
 * @property-read string $event Event type name (e.g., "endpoint.created")
 * @property-read array $data Event payload data
 * @property-read string $timestamp ISO 8601 timestamp string
 */
class WebhookEvent
{
    /** @var string */
    private $event;

    /** @var array */
    private $data;

    /** @var string */
    private $timestamp;

    /** @var string[] Known event types */
    const EVENT_TYPE_MAP = [
        'endpoint.created',
        'endpoint.updated',
        'endpoint.deleted',
        'endpoint.enabled',
        'endpoint.disabled',
        'message.attempt.exhausted',
        'message.attempt.failing',
        'message.attempt.recovered',
    ];

    public function __construct(string $event, array $data, string $timestamp)
    {
        $this->event = $event;
        $this->data = $data;
        $this->timestamp = $timestamp;
    }

    /**
     * Get the event type name (e.g., "endpoint.created").
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * Alias for getEvent() — the event type name.
     */
    public function getEventType(): string
    {
        return $this->event;
    }

    /**
     * Get the event payload data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the ISO 8601 timestamp string.
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * Get a value from the data array by key.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function __get($name)
    {
        if ($name === 'event') return $this->event;
        if ($name === 'data') return $this->data;
        if ($name === 'timestamp') return $this->timestamp;
        return $this->data[$name] ?? null;
    }

    public function __isset($name)
    {
        if (in_array($name, ['event', 'data', 'timestamp'])) return true;
        return isset($this->data[$name]);
    }

    public function __toString(): string
    {
        return "WebhookEvent{event='{$this->event}', timestamp='{$this->timestamp}'}";
    }

    // ─── Typed Data Parsing ──────────────────────────────────────

    /**
     * Parse event data as EndpointCreatedData.
     * @return EndpointCreatedData
     */
    public function parseEndpointCreatedData(): EndpointCreatedData
    {
        return new EndpointCreatedData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['endpointId'] ?? $this->data['endpoint_id'] ?? '',
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null
        );
    }

    /**
     * Parse event data as EndpointUpdatedData.
     * @return EndpointUpdatedData
     */
    public function parseEndpointUpdatedData(): EndpointUpdatedData
    {
        return new EndpointUpdatedData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['endpointId'] ?? $this->data['endpoint_id'] ?? '',
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null
        );
    }

    /**
     * Parse event data as EndpointDeletedData.
     * @return EndpointDeletedData
     */
    public function parseEndpointDeletedData(): EndpointDeletedData
    {
        return new EndpointDeletedData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['endpointId'] ?? $this->data['endpoint_id'] ?? '',
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null
        );
    }

    /**
     * Parse event data as EndpointEnabledData.
     * @return EndpointEnabledData
     */
    public function parseEndpointEnabledData(): EndpointEnabledData
    {
        return new EndpointEnabledData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['endpointId'] ?? $this->data['endpoint_id'] ?? '',
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null
        );
    }

    /**
     * Parse event data as EndpointDisabledData.
     * @return EndpointDisabledData
     */
    public function parseEndpointDisabledData(): EndpointDisabledData
    {
        return new EndpointDisabledData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['endpointId'] ?? $this->data['endpoint_id'] ?? '',
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null,
            $this->data['failSince'] ?? $this->data['fail_since'] ?? null,
            $this->data['trigger'] ?? null
        );
    }

    /**
     * Parse event data as MessageAttemptExhaustedData.
     * @return MessageAttemptExhaustedData
     */
    public function parseMessageAttemptExhaustedData(): MessageAttemptExhaustedData
    {
        $lastRaw = $this->data['lastAttempt'] ?? $this->data['last_attempt'] ?? [];
        return new MessageAttemptExhaustedData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['msgId'] ?? $this->data['msg_id'] ?? '',
            new LastAttemptInfo(
                $lastRaw['id'] ?? '',
                $lastRaw['timestamp'] ?? '',
                $lastRaw['responseStatusCode'] ?? $lastRaw['response_status_code'] ?? 0
            ),
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null
        );
    }

    /**
     * Parse event data as MessageAttemptFailingData.
     * @return MessageAttemptFailingData
     */
    public function parseMessageAttemptFailingData(): MessageAttemptFailingData
    {
        $attRaw = $this->data['attempt'] ?? [];
        return new MessageAttemptFailingData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['msgId'] ?? $this->data['msg_id'] ?? '',
            new AttemptInfo(
                $attRaw['id'] ?? '',
                $attRaw['timestamp'] ?? '',
                $attRaw['responseStatusCode'] ?? $attRaw['response_status_code'] ?? 0
            ),
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null
        );
    }

    /**
     * Parse event data as MessageAttemptRecoveredData.
     * @return MessageAttemptRecoveredData
     */
    public function parseMessageAttemptRecoveredData(): MessageAttemptRecoveredData
    {
        $attRaw = $this->data['attempt'] ?? [];
        return new MessageAttemptRecoveredData(
            $this->data['appId'] ?? $this->data['app_id'] ?? '',
            $this->data['msgId'] ?? $this->data['msg_id'] ?? '',
            new AttemptInfo(
                $attRaw['id'] ?? '',
                $attRaw['timestamp'] ?? '',
                $attRaw['responseStatusCode'] ?? $attRaw['response_status_code'] ?? 0
            ),
            $this->data['appUid'] ?? $this->data['app_uid'] ?? null
        );
    }

    /**
     * Parse a webhook payload array into a typed WebhookEvent.
     *
     * @param array $data Parsed JSON payload with 'event', 'data', 'timestamp' keys
     * @return self
     */
    public static function parse(array $data): self
    {
        $event = $data['event'] ?? $data['eventType'] ?? '';
        $payload = $data['data'] ?? [];
        $timestamp = $data['timestamp'] ?? '';

        return new self($event, $payload, $timestamp);
    }
}
