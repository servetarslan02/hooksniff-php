<?php
namespace HookSniff\WebhookEvents;
class MessageAttemptExhaustedData {
    public string $appId;
    public string $msgId;
    public LastAttemptInfo $lastAttempt;
    public ?string $appUid;
    public function __construct(string $appId, string $msgId, LastAttemptInfo $lastAttempt, ?string $appUid = null) {
        $this->appId = $appId; $this->msgId = $msgId; $this->lastAttempt = $lastAttempt; $this->appUid = $appUid;
    }
}
