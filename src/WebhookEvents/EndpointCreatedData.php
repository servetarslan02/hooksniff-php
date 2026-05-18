<?php
namespace HookSniff\WebhookEvents;
class EndpointCreatedData {
    public string $appId;
    public string $endpointId;
    public ?string $appUid;
    public function __construct(string $appId, string $endpointId, ?string $appUid = null) {
        $this->appId = $appId; $this->endpointId = $endpointId; $this->appUid = $appUid;
    }
}
