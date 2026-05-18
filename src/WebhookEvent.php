<?php

namespace HookSniff;

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

    /**
     * Access data values with bracket notation (ArrayAccess).
     */
    public function offsetGet($key)
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

    /**
     * Parse a webhook payload array into a WebhookEvent.
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
