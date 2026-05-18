<?php
namespace HookSniff\WebhookEvents;
class LastAttemptInfo {
    public string $id;
    public string $timestamp;
    public int $responseStatusCode;
    public function __construct(string $id, string $timestamp, int $responseStatusCode) {
        $this->id = $id; $this->timestamp = $timestamp; $this->responseStatusCode = $responseStatusCode;
    }
}
