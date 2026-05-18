<?php
namespace HookSniff\WebhookEvents;
class EndpointDisabledData {
    public string $appId;
    public string $endpointId;
    public ?string $appUid;
    public ?string $failSince;
    public ?string $trigger;
    public function __construct(string $appId, string $endpointId, ?string $appUid = null, ?string $failSince = null, ?string $trigger = null) {
        $this->appId = $appId; $this->endpointId = $endpointId; $this->appUid = $appUid;
        $this->failSince = $failSince; $this->trigger = $trigger;
    }
}
