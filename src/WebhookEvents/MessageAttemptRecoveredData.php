<?php
namespace HookSniff\WebhookEvents;
class MessageAttemptRecoveredData {
    public string $appId;
    public string $msgId;
    public AttemptInfo $attempt;
    public ?string $appUid;
    public function __construct(string $appId, string $msgId, AttemptInfo $attempt, ?string $appUid = null) {
        $this->appId = $appId; $this->msgId = $msgId; $this->attempt = $attempt; $this->appUid = $appUid;
    }
}
