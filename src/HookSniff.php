<?php

declare(strict_types=1);

namespace HookSniff;

use GuzzleHttp\Client;
use HookSniff\Api\Admin;
use HookSniff\Api\Alert;
use HookSniff\Api\Analytics;
use HookSniff\Api\ApiKey;
use HookSniff\Api\Application;
use HookSniff\Api\Authentication;
use HookSniff\Api\AuditLog;
use HookSniff\Api\BackgroundTask;
use HookSniff\Api\Billing;
use HookSniff\Api\Connector;
use HookSniff\Api\CustomDomain;
use HookSniff\Api\Device;
use HookSniff\Api\Endpoint;
use HookSniff\Api\Environment;
use HookSniff\Api\EventType;
use HookSniff\Api\Health;
use HookSniff\Api\Inbound;
use HookSniff\Api\Integration;
use HookSniff\Api\Message;
use HookSniff\Api\MessageAttempt;
use HookSniff\Api\MessagePoller;
use HookSniff\Api\Notification;
use HookSniff\Api\OperationalWebhook;
use HookSniff\Api\Portal;
use HookSniff\Api\RateLimit;
use HookSniff\Api\Routing;
use HookSniff\Api\Schema;
use HookSniff\Api\Search;
use HookSniff\Api\ServiceToken;
use HookSniff\Api\Sso;
use HookSniff\Api\Statistics;
use HookSniff\Api\Stream;
use HookSniff\Api\Team;
use HookSniff\Api\Template;
use HookSniff\Api\Transform;
use HookSniff\Request\HookSniffHttpClient;

class HookSniff
{
    public Admin $admin;
    public Alert $alert;
    public Analytics $analytics;
    public ApiKey $apiKey;
    public Application $application;
    public Authentication $authentication;
    public AuditLog $auditLog;
    public BackgroundTask $backgroundTask;
    public Billing $billing;
    public Connector $connector;
    public CustomDomain $customDomain;
    public Device $device;
    public Endpoint $endpoint;
    public Environment $environment;
    public EventType $eventType;
    public Health $health;
    public Inbound $inbound;
    public Integration $integration;
    public Message $message;
    public MessageAttempt $messageAttempt;
    public MessagePoller $messagePoller;
    public Notification $notification;
    public OperationalWebhook $operationalWebhook;
    public Portal $portal;
    public RateLimit $rateLimit;
    public Routing $routing;
    public Schema $schema;
    public Search $search;
    public ServiceToken $serviceToken;
    public Sso $sso;
    public Statistics $statistics;
    public Stream $stream;
    public Team $team;
    public Template $template;
    public Transform $transform;

    public function __construct(
        string $token,
        ?HookSniffOptions $options = null,
        ?Client $httpClient = null,
    ) {
        $baseUrl = $options?->serverUrl ?? 'https://hooksniff-api-1046140057667.europe-west1.run.app';

        $hooksniffHttpClient = new HookSniffHttpClient(
            token: $token,
            baseUrl: $baseUrl,
            guzzleClient: $httpClient ?? new Client(),
            opts: $options ?? HookSniffOptions::newDefault($token),
        );

        $this->admin = new Admin($hooksniffHttpClient);
        $this->alert = new Alert($hooksniffHttpClient);
        $this->analytics = new Analytics($hooksniffHttpClient);
        $this->apiKey = new ApiKey($hooksniffHttpClient);
        $this->application = new Application($hooksniffHttpClient);
        $this->authentication = new Authentication($hooksniffHttpClient);
        $this->auditLog = new AuditLog($hooksniffHttpClient);
        $this->backgroundTask = new BackgroundTask($hooksniffHttpClient);
        $this->billing = new Billing($hooksniffHttpClient);
        $this->connector = new Connector($hooksniffHttpClient);
        $this->customDomain = new CustomDomain($hooksniffHttpClient);
        $this->device = new Device($hooksniffHttpClient);
        $this->endpoint = new Endpoint($hooksniffHttpClient);
        $this->environment = new Environment($hooksniffHttpClient);
        $this->eventType = new EventType($hooksniffHttpClient);
        $this->health = new Health($hooksniffHttpClient);
        $this->inbound = new Inbound($hooksniffHttpClient);
        $this->integration = new Integration($hooksniffHttpClient);
        $this->message = new Message($hooksniffHttpClient);
        $this->messageAttempt = new MessageAttempt($hooksniffHttpClient);
        $this->messagePoller = new MessagePoller($hooksniffHttpClient);
        $this->notification = new Notification($hooksniffHttpClient);
        $this->operationalWebhook = new OperationalWebhook($hooksniffHttpClient);
        $this->portal = new Portal($hooksniffHttpClient);
        $this->rateLimit = new RateLimit($hooksniffHttpClient);
        $this->routing = new Routing($hooksniffHttpClient);
        $this->schema = new Schema($hooksniffHttpClient);
        $this->search = new Search($hooksniffHttpClient);
        $this->serviceToken = new ServiceToken($hooksniffHttpClient);
        $this->sso = new Sso($hooksniffHttpClient);
        $this->statistics = new Statistics($hooksniffHttpClient);
        $this->stream = new Stream($hooksniffHttpClient);
        $this->team = new Team($hooksniffHttpClient);
        $this->template = new Template($hooksniffHttpClient);
        $this->transform = new Transform($hooksniffHttpClient);
    }
}
