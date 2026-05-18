<?php

namespace HookSniff\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use HookSniff\HookSniff;
use HookSniff\HookSniffOptions;
use HookSniff\Version;

const HealthResponse = '{"status":"ok"}';
const AlertsList = '{"data":[{"id":"alert_123","name":"Test Alert","condition":"failure_rate","threshold":10}],"done":true}';
const AnalyticsDeliveries = '{"data":[{"date":"2026-05-01","count":100}]}';
const BillingSubscription = '{"plan":"pro","status":"active","current_period_end":"2026-06-01"}';
const BillingUsage = '{"webhooks":{"used":500,"limit":10000}}';
const TeamList = '{"data":[{"id":"team_123","name":"Test Team"}],"done":true}';
const NotificationList = '{"data":[{"id":"notif_123","title":"Test","read":false}],"done":true}';
const SsoConfig = '{"id":"sso_123","provider":"saml","enabled":true}';
const SchemaList = '{"data":[{"id":"schema_123","name":"user.created"}],"done":true}';
const AdminStats = '{"total_users":100,"total_webhooks":5000,"total_endpoints":200}';
const EnvironmentList = '{"data":[{"id":"env_123","name":"production"}],"done":true}';
const ConnectorList = '{"data":[{"id":"conn_123","name":"Slack Connector","kind":"Slack"}],"done":true}';
const IntegrationList = '{"data":[{"id":"integ_123","name":"Test Integration"}],"done":true}';
const StreamChannels = '{"data":[{"id":"ch_123","name":"events"}],"done":true}';
const SearchResult = '{"data":[{"id":"del_123","event_type":"user.created"}],"done":true}';
const ApiKeyList = '{"data":[{"id":"key_123","name":"Production Key","prefix":"sk_live_"}],"done":true}';
const ApplicationList = '{"data":[{"id":"app_123","name":"My App","uid":"unique-id"}],"done":true}';
const ServiceTokenList = '{"data":[{"id":"st_123","name":"CI Token"}],"done":true}';
const TemplateList = '{"data":[{"id":"tpl_123","name":"Slack Template"}],"done":true}';
const AuditLogList = '{"data":[{"id":"audit_123","action":"user.login"}],"done":true}';
const CustomDomainList = '{"data":[{"id":"cd_123","domain":"hooks.example.com"}],"done":true}';
const RateLimitList = '{"data":[{"endpoint_id":"ep_123","requests_per_minute":100}],"done":true}';
const BackgroundTaskList = '{"data":[{"id":"bt_123","status":"running","task":"endpoint.replay"}],"done":true}';
const OperationalWebhookList = '{"data":[{"id":"ow_123","url":"https://example.com/hook"}],"done":true}';
const MessagePollResult = '{"data":[{"id":"msg_123","event_type":"user.created"}],"cursor":"abc123"}';
const InboundConfigList = '{"data":[{"id":"ic_123","provider":"stripe"}],"done":true}';
const PortalConfig = '{"id":"portal_123","name":"My Portal","enabled":true}';
const DeviceList = '{"data":[{"id":"dev_123","platform":"ios"}],"done":true}';

class MockTest extends TestCase
{
    private array $requestHistory = [];
    private MockHandler $mockHandler;
    private Client $httpClient;

    protected function setUp(): void
    {
        $this->requestHistory = [];
        $this->mockHandler = new MockHandler();

        $handlerStack = HandlerStack::create($this->mockHandler);
        $handlerStack->push(Middleware::history($this->requestHistory));

        $this->httpClient = new Client(['handler' => $handlerStack]);
    }

    private function createClient(): HookSniff
    {
        return new HookSniff("test_token_123", httpClient: $this->httpClient);
    }

    // ── Basic Headers ───────────────────────────────────────

    public function testBasicHeaders(): void
    {
        $this->mockHandler->append(new Response(200, [], HealthResponse));

        $client = $this->createClient();
        $client->health->get();

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('hooksniff-libs/' . Version::VERSION . '/php', $req->getHeaderLine('User-Agent'));
        $this->assertEquals('Bearer test_token_123', $req->getHeaderLine('Authorization'));
        $this->assertIsString($req->getHeaderLine('hooksniff-req-id'));
    }

    // ── Base URL ────────────────────────────────────────────

    public function testDefaultBaseUrl(): void
    {
        $this->mockHandler->append(new Response(200, [], HealthResponse));

        $client = $this->createClient();
        $client->health->get();

        $req = $this->requestHistory[0]['request'];
        $this->assertStringContainsString('hooksniff-api-1046140057667.europe-west1.run.app', (string) $req->getUri());
    }

    public function testCustomBaseUrl(): void
    {
        $this->mockHandler->append(new Response(200, [], HealthResponse));

        $options = new HookSniffOptions(serverUrl: 'https://custom.api.com');
        $client = new HookSniff("token", $options, $this->httpClient);
        $client->health->get();

        $req = $this->requestHistory[0]['request'];
        $this->assertStringContainsString('custom.api.com', (string) $req->getUri());
    }

    // ── Idempotency Key ─────────────────────────────────────

    public function testAutoIdempotencyKey(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"msg_123","eventType":"test"}'));

        $client = $this->createClient();
        $client->message->create(new \HookSniff\Models\MessageIn('test.event', ['key' => 'value']));

        $req = $this->requestHistory[0]['request'];
        $this->assertStringStartsWith('auto_', $req->getHeaderLine('idempotency-key'));
    }

    // ── Content-Type ────────────────────────────────────────

    public function testContentTypeOnPost(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"msg_123"}'));

        $client = $this->createClient();
        $client->message->create(new \HookSniff\Models\MessageIn('test.event', ['key' => 'value']));

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('application/json', $req->getHeaderLine('Content-Type'));
    }

    public function testNoContentTypeOnGet(): void
    {
        $this->mockHandler->append(new Response(200, [], HealthResponse));

        $client = $this->createClient();
        $client->health->get();

        $req = $this->requestHistory[0]['request'];
        $this->assertFalse($req->hasHeader('Content-Type'));
    }

    // ── API Resources ───────────────────────────────────────

    public function testAlertList(): void
    {
        $this->mockHandler->append(new Response(200, [], AlertsList));

        $client = $this->createClient();
        $result = $client->alert->list();

        $this->assertIsArray($result);
        $this->assertCount(1, $result['data']);
        $this->assertEquals('alert_123', $result['data'][0]['id']);
    }

    public function testAlertCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"alert_new","name":"New Alert"}'));

        $client = $this->createClient();
        $result = $client->alert->create(['name' => 'New Alert', 'condition' => 'failure_rate', 'threshold' => 10]);

        $this->assertEquals('alert_new', $result['id']);
        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $req->getMethod());
    }

    public function testAlertDelete(): void
    {
        $this->mockHandler->append(new Response(204, []));

        $client = $this->createClient();
        $client->alert->delete('alert_123');

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('DELETE', $req->getMethod());
    }

    public function testAlertTest(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"sent":true}'));

        $client = $this->createClient();
        $result = $client->alert->test('alert_123');

        $this->assertTrue($result['sent']);
    }

    public function testAnalyticsDeliveryTrend(): void
    {
        $this->mockHandler->append(new Response(200, [], AnalyticsDeliveries));

        $client = $this->createClient();
        $result = $client->analytics->deliveryTrend(['start_date' => '2026-05-01']);

        $this->assertIsArray($result['data']);
    }

    public function testAnalyticsSuccessRate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"rate":0.98}'));

        $client = $this->createClient();
        $result = $client->analytics->successRate();

        $this->assertEquals(0.98, $result['rate']);
    }

    public function testAnalyticsLatencyTrend(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"avg_ms":150}]}'));

        $client = $this->createClient();
        $result = $client->analytics->latencyTrend();

        $this->assertEquals(150, $result['data'][0]['avg_ms']);
    }

    public function testBillingGetSubscription(): void
    {
        $this->mockHandler->append(new Response(200, [], BillingSubscription));

        $client = $this->createClient();
        $result = $client->billing->getSubscription();

        $this->assertEquals('pro', $result['plan']);
        $this->assertEquals('active', $result['status']);
    }

    public function testBillingGetUsage(): void
    {
        $this->mockHandler->append(new Response(200, [], BillingUsage));

        $client = $this->createClient();
        $result = $client->billing->getUsage();

        $this->assertEquals(500, $result['webhooks']['used']);
    }

    public function testBillingOpenPortal(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"url":"https://billing.stripe.com/session"}'));

        $client = $this->createClient();
        $result = $client->billing->openPortal();

        $this->assertStringContainsString('stripe.com', $result['url']);
    }

    public function testBillingUpgrade(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"checkout_url":"https://checkout.stripe.com"}'));

        $client = $this->createClient();
        $result = $client->billing->upgrade(['plan' => 'business']);

        $this->assertStringContainsString('checkout', $result['checkout_url']);
    }

    public function testTeamList(): void
    {
        $this->mockHandler->append(new Response(200, [], TeamList));

        $client = $this->createClient();
        $result = $client->team->list();

        $this->assertCount(1, $result['data']);
        $this->assertEquals('Test Team', $result['data'][0]['name']);
    }

    public function testTeamCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"team_new","name":"New Team"}'));

        $client = $this->createClient();
        $result = $client->team->create(['name' => 'New Team']);

        $this->assertEquals('team_new', $result['id']);
    }

    public function testTeamInviteMember(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"invited":true}'));

        $client = $this->createClient();
        $result = $client->team->inviteMember('team_123', ['email' => 'user@example.com']);

        $this->assertTrue($result['invited']);
    }

    public function testTeamRemoveMember(): void
    {
        $this->mockHandler->append(new Response(204, []));

        $client = $this->createClient();
        $client->team->removeMember('team_123', 'user_456');

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('DELETE', $req->getMethod());
        $this->assertStringContainsString('/teams/team_123/members/user_456', (string) $req->getUri());
    }

    public function testNotificationList(): void
    {
        $this->mockHandler->append(new Response(200, [], NotificationList));

        $client = $this->createClient();
        $result = $client->notification->list();

        $this->assertCount(1, $result['data']);
    }

    public function testNotificationUnreadCount(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"count":5}'));

        $client = $this->createClient();
        $result = $client->notification->unreadCount();

        $this->assertEquals(5, $result['count']);
    }

    public function testNotificationMarkAllRead(): void
    {
        $this->mockHandler->append(new Response(204, []));

        $client = $this->createClient();
        $client->notification->markAllRead();

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('PUT', $req->getMethod());
    }

    public function testSsoGetConfig(): void
    {
        $this->mockHandler->append(new Response(200, [], SsoConfig));

        $client = $this->createClient();
        $result = $client->sso->getConfig();

        $this->assertEquals('saml', $result['provider']);
    }

    public function testSsoUpsertConfig(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"sso_new","provider":"oidc"}'));

        $client = $this->createClient();
        $result = $client->sso->upsertConfig(['provider' => 'oidc', 'enabled' => true]);

        $this->assertEquals('oidc', $result['provider']);
    }

    public function testSsoDeleteConfig(): void
    {
        $this->mockHandler->append(new Response(204, []));

        $client = $this->createClient();
        $client->sso->deleteConfig();

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('DELETE', $req->getMethod());
    }

    public function testSchemaList(): void
    {
        $this->mockHandler->append(new Response(200, [], SchemaList));

        $client = $this->createClient();
        $result = $client->schema->list();

        $this->assertCount(1, $result['data']);
    }

    public function testSchemaRegister(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"schema_new","name":"order.created"}'));

        $client = $this->createClient();
        $result = $client->schema->register(['name' => 'order.created', 'schema' => ['type' => 'object']]);

        $this->assertEquals('schema_new', $result['id']);
    }

    public function testSchemaValidate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"valid":true}'));

        $client = $this->createClient();
        $result = $client->schema->validate('schema_123', ['event_type' => 'user.created', 'payload' => ['email' => 'test@test.com']]);

        $this->assertTrue($result['valid']);
    }

    public function testAdminGetStats(): void
    {
        $this->mockHandler->append(new Response(200, [], AdminStats));

        $client = $this->createClient();
        $result = $client->admin->getSystemStats();

        $this->assertEquals(100, $result['total_users']);
    }

    public function testAdminListUsers(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"id":"user_1","email":"admin@test.com"}],"done":true}'));

        $client = $this->createClient();
        $result = $client->admin->listUsers();

        $this->assertCount(1, $result['data']);
    }

    public function testAdminImpersonateUser(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"token":"impersonate_token_123","expires_in":3600}'));

        $client = $this->createClient();
        $result = $client->admin->impersonateUser('user_123');

        $this->assertStringStartsWith('impersonate_', $result['token']);
    }

    public function testEnvironmentList(): void
    {
        $this->mockHandler->append(new Response(200, [], EnvironmentList));

        $client = $this->createClient();
        $result = $client->environment->list();

        $this->assertCount(1, $result['data']);
        $this->assertEquals('production', $result['data'][0]['name']);
    }

    public function testEnvironmentCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"env_new","name":"staging"}'));

        $client = $this->createClient();
        $result = $client->environment->create(['name' => 'staging']);

        $this->assertEquals('env_new', $result['id']);
    }

    public function testConnectorList(): void
    {
        $this->mockHandler->append(new Response(200, [], ConnectorList));

        $client = $this->createClient();
        $result = $client->connector->list();

        $this->assertCount(1, $result['data']);
        $this->assertEquals('Slack', $result['data'][0]['kind']);
    }

    public function testIntegrationList(): void
    {
        $this->mockHandler->append(new Response(200, [], IntegrationList));

        $client = $this->createClient();
        $result = $client->integration->list();

        $this->assertCount(1, $result['data']);
    }

    public function testIntegrationCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"integ_new","name":"New Integration"}'));

        $client = $this->createClient();
        $result = $client->integration->create(['name' => 'New Integration']);

        $this->assertEquals('integ_new', $result['id']);
    }

    public function testStreamListChannels(): void
    {
        $this->mockHandler->append(new Response(200, [], StreamChannels));

        $client = $this->createClient();
        $result = $client->stream->listChannels();

        $this->assertCount(1, $result['data']);
    }

    public function testStreamPublish(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"msg_stream_123","channel_id":"ch_123"}'));

        $client = $this->createClient();
        $result = $client->stream->publish(['channel_id' => 'ch_123', 'data' => ['event' => 'test']]);

        $this->assertEquals('msg_stream_123', $result['id']);
    }

    public function testSearch(): void
    {
        $this->mockHandler->append(new Response(200, [], SearchResult));

        $client = $this->createClient();
        $result = $client->search->search(['q' => 'user.created']);

        $this->assertCount(1, $result['data']);
    }

    public function testApiKeyList(): void
    {
        $this->mockHandler->append(new Response(200, [], ApiKeyList));

        $client = $this->createClient();
        $result = $client->apiKey->list();

        $this->assertCount(1, $result['data']);
    }

    public function testApiKeyCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"key_new","name":"New Key","key":"sk_live_abc123"}'));

        $client = $this->createClient();
        $result = $client->apiKey->create(['name' => 'New Key']);

        $this->assertStringStartsWith('sk_live_', $result['key']);
    }

    public function testApiKeyRotate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"key_123","key":"sk_live_rotated"}'));

        $client = $this->createClient();
        $result = $client->apiKey->rotate('key_123');

        $this->assertStringStartsWith('sk_live_', $result['key']);
    }

    public function testApplicationList(): void
    {
        $this->mockHandler->append(new Response(200, [], ApplicationList));

        $client = $this->createClient();
        $result = $client->application->list();

        $this->assertCount(1, $result['data']);
    }

    public function testApplicationCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"app_new","name":"New App"}'));

        $client = $this->createClient();
        $result = $client->application->create(['name' => 'New App']);

        $this->assertEquals('app_new', $result['id']);
    }

    public function testServiceTokenList(): void
    {
        $this->mockHandler->append(new Response(200, [], ServiceTokenList));

        $client = $this->createClient();
        $result = $client->serviceToken->list();

        $this->assertCount(1, $result['data']);
    }

    public function testServiceTokenCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"st_new","name":"New Token","token":"st_abc123"}'));

        $client = $this->createClient();
        $result = $client->serviceToken->create(['name' => 'New Token']);

        $this->assertStringStartsWith('st_', $result['token']);
    }

    public function testServiceTokenReveal(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"st_123","token":"st_revealed_secret"}'));

        $client = $this->createClient();
        $result = $client->serviceToken->reveal('st_123');

        $this->assertStringStartsWith('st_', $result['token']);
    }

    public function testTemplateList(): void
    {
        $this->mockHandler->append(new Response(200, [], TemplateList));

        $client = $this->createClient();
        $result = $client->template->list();

        $this->assertCount(1, $result['data']);
    }

    public function testTemplateApply(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"applied":true}'));

        $client = $this->createClient();
        $result = $client->template->apply('tpl_123');

        $this->assertTrue($result['applied']);
    }

    public function testAuditLogList(): void
    {
        $this->mockHandler->append(new Response(200, [], AuditLogList));

        $client = $this->createClient();
        $result = $client->auditLog->list();

        $this->assertCount(1, $result['data']);
    }

    public function testCustomDomainList(): void
    {
        $this->mockHandler->append(new Response(200, [], CustomDomainList));

        $client = $this->createClient();
        $result = $client->customDomain->list();

        $this->assertCount(1, $result['data']);
    }

    public function testCustomDomainAdd(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"cd_new","domain":"new.example.com","cname_target":"cname.hooksniff.com"}'));

        $client = $this->createClient();
        $result = $client->customDomain->add(['domain' => 'new.example.com']);

        $this->assertEquals('new.example.com', $result['domain']);
    }

    public function testCustomDomainVerify(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"verified":true}'));

        $client = $this->createClient();
        $result = $client->customDomain->verify('cd_123');

        $this->assertTrue($result['verified']);
    }

    public function testRateLimitList(): void
    {
        $this->mockHandler->append(new Response(200, [], RateLimitList));

        $client = $this->createClient();
        $result = $client->rateLimit->list();

        $this->assertCount(1, $result['data']);
    }

    public function testRateLimitSet(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"endpoint_id":"ep_123","requests_per_minute":200}'));

        $client = $this->createClient();
        $result = $client->rateLimit->set('ep_123', ['requests_per_minute' => 200]);

        $this->assertEquals(200, $result['requests_per_minute']);
    }

    public function testRateLimitDelete(): void
    {
        $this->mockHandler->append(new Response(204, []));

        $client = $this->createClient();
        $client->rateLimit->delete('ep_123');

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('DELETE', $req->getMethod());
    }

    public function testRoutingGet(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"endpoint_id":"ep_123","rules":[]}'));

        $client = $this->createClient();
        $result = $client->routing->get('ep_123');

        $this->assertEquals('ep_123', $result['endpoint_id']);
    }

    public function testRoutingUpdate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"endpoint_id":"ep_123","rules":[{"event_type":"user.created"}]}'));

        $client = $this->createClient();
        $result = $client->routing->update('ep_123', ['rules' => [['event_type' => 'user.created']]]);

        $this->assertCount(1, $result['rules']);
    }

    public function testBackgroundTaskList(): void
    {
        $this->mockHandler->append(new Response(200, [], BackgroundTaskList));

        $client = $this->createClient();
        $result = $client->backgroundTask->list();

        $this->assertCount(1, $result['data']);
    }

    public function testBackgroundTaskGet(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"bt_123","status":"finished","task":"endpoint.replay"}'));

        $client = $this->createClient();
        $result = $client->backgroundTask->get('bt_123');

        $this->assertEquals('finished', $result['status']);
    }

    public function testBackgroundTaskCancel(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"bt_123","status":"cancelled"}'));

        $client = $this->createClient();
        $result = $client->backgroundTask->cancel('bt_123');

        $this->assertEquals('cancelled', $result['status']);
    }

    public function testOperationalWebhookList(): void
    {
        $this->mockHandler->append(new Response(200, [], OperationalWebhookList));

        $client = $this->createClient();
        $result = $client->operationalWebhook->list();

        $this->assertCount(1, $result['data']);
    }

    public function testOperationalWebhookCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"ow_new","url":"https://example.com/new"}'));

        $client = $this->createClient();
        $result = $client->operationalWebhook->create(['url' => 'https://example.com/new', 'events' => ['delivery.failed']]);

        $this->assertEquals('ow_new', $result['id']);
    }

    public function testMessagePollerPoll(): void
    {
        $this->mockHandler->append(new Response(200, [], MessagePollResult));

        $client = $this->createClient();
        $result = $client->messagePoller->poll();

        $this->assertCount(1, $result['data']);
        $this->assertArrayHasKey('cursor', $result);
    }

    public function testMessagePollerSeek(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"cursor":"seek_abc123"}'));

        $client = $this->createClient();
        $result = $client->messagePoller->seek(['cursor' => 'abc123']);

        $this->assertStringStartsWith('seek_', $result['cursor']);
    }

    public function testMessagePollerCommit(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"committed":true}'));

        $client = $this->createClient();
        $result = $client->messagePoller->commit(['cursor' => 'abc123']);

        $this->assertTrue($result['committed']);
    }

    public function testInboundListConfigs(): void
    {
        $this->mockHandler->append(new Response(200, [], InboundConfigList));

        $client = $this->createClient();
        $result = $client->inbound->listConfigs();

        $this->assertCount(1, $result['data']);
    }

    public function testInboundCreateConfig(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"ic_new","provider":"github"}'));

        $client = $this->createClient();
        $result = $client->inbound->createConfig(['provider' => 'github', 'secret' => 'webhook_secret']);

        $this->assertEquals('github', $result['provider']);
    }

    public function testInboundHandle(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"received":true}'));

        $client = $this->createClient();
        $result = $client->inbound->handle('stripe', ['type' => 'payment_intent.succeeded'], ['stripe-signature' => 'sig_123']);

        $this->assertTrue($result['received']);
    }

    public function testPortalGetConfig(): void
    {
        $this->mockHandler->append(new Response(200, [], PortalConfig));

        $client = $this->createClient();
        $result = $client->portal->getConfig();

        $this->assertEquals('My Portal', $result['name']);
    }

    public function testPortalUpdateConfig(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"portal_123","name":"Updated Portal"}'));

        $client = $this->createClient();
        $result = $client->portal->updateConfig(['name' => 'Updated Portal']);

        $this->assertEquals('Updated Portal', $result['name']);
    }

    public function testPortalGetEmbedCode(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"embed_code":"<script src=\\"https://cdn.hooksniff.com/portal.js\\"></script>"}'));

        $client = $this->createClient();
        $result = $client->portal->getEmbedCode();

        $this->assertStringContainsString('hooksniff.com', $result['embed_code']);
    }

    public function testDeviceList(): void
    {
        $this->mockHandler->append(new Response(200, [], DeviceList));

        $client = $this->createClient();
        $result = $client->device->list();

        $this->assertCount(1, $result['data']);
    }

    public function testDeviceRegister(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"dev_new","platform":"android"}'));

        $client = $this->createClient();
        $result = $client->device->register(['platform' => 'android', 'token' => 'fcm_token_123']);

        $this->assertEquals('dev_new', $result['id']);
    }

    public function testTransformList(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"id":"tr_123","name":"Add Header"}],"done":true}'));

        $client = $this->createClient();
        $result = $client->transform->list();

        $this->assertCount(1, $result['data']);
    }

    public function testTransformCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"tr_new","name":"New Transform"}'));

        $client = $this->createClient();
        $result = $client->transform->create(['name' => 'New Transform', 'type' => 'header', 'config' => ['key' => 'X-Custom', 'value' => 'test']]);

        $this->assertEquals('tr_new', $result['id']);
    }

    public function testTransformTest(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"success":true,"output":{"headers":{"X-Custom":"test"}}}'));

        $client = $this->createClient();
        $result = $client->transform->test(['type' => 'header', 'input' => ['headers' => []]]);

        $this->assertTrue($result['success']);
    }

    // ── Endpoint CRUD ───────────────────────────────────────

    public function testEndpointCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"ep_new","url":"https://example.com/hook","description":"Test"}'));

        $client = $this->createClient();
        $result = $client->endpoint->create(new \HookSniff\Models\EndpointIn('https://example.com/hook'));

        $this->assertEquals('ep_new', $result['id']);
        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $req->getMethod());
    }

    public function testEndpointList(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"id":"ep_123","url":"https://example.com/hook"}],"done":true}'));

        $client = $this->createClient();
        $result = $client->endpoint->list();

        $this->assertCount(1, $result->data);
    }

    public function testEndpointGet(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"ep_123","url":"https://example.com/hook"}'));

        $client = $this->createClient();
        $result = $client->endpoint->get('ep_123');

        $this->assertEquals('ep_123', $result->id);
    }

    public function testEndpointDelete(): void
    {
        $this->mockHandler->append(new Response(204, []));

        $client = $this->createClient();
        $client->endpoint->delete('ep_123');

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('DELETE', $req->getMethod());
    }

    public function testEndpointGetSecret(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"key":"whsec_abc123"}'));

        $client = $this->createClient();
        $result = $client->endpoint->getSecret('ep_123');

        $this->assertStringStartsWith('whsec_', $result->key);
    }

    // ── Message CRUD ────────────────────────────────────────

    public function testMessageCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"msg_123","eventType":"user.created","payload":{"email":"test@example.com"}}'));

        $client = $this->createClient();
        $result = $client->message->create(new \HookSniff\Models\MessageIn('user.created', ['email' => 'test@example.com']));

        $this->assertEquals('msg_123', $result->id);
        $this->assertEquals('user.created', $result->eventType);
    }

    public function testMessageList(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"id":"msg_123","eventType":"user.created"}],"done":true}'));

        $client = $this->createClient();
        $result = $client->message->list();

        $this->assertCount(1, $result->data);
    }

    public function testMessageGet(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"msg_123","eventType":"user.created"}'));

        $client = $this->createClient();
        $result = $client->message->get('msg_123');

        $this->assertEquals('msg_123', $result->id);
    }

    // ── Message Attempt ─────────────────────────────────────

    public function testMessageAttemptListByEndpoint(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"id":"atmpt_123","msgId":"msg_123","endpointId":"ep_123","status":0}],"done":true}'));

        $client = $this->createClient();
        $result = $client->messageAttempt->listByEndpoint('ep_123');

        $this->assertCount(1, $result->data);
    }

    public function testMessageAttemptListByMsg(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"id":"atmpt_123","msgId":"msg_123","status":0}],"done":true}'));

        $client = $this->createClient();
        $result = $client->messageAttempt->listByMsg('msg_123');

        $this->assertCount(1, $result->data);
    }

    public function testMessageAttemptGet(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"id":"atmpt_123","msgId":"msg_123","status":0}'));

        $client = $this->createClient();
        $result = $client->messageAttempt->get('msg_123', 'atmpt_123');

        $this->assertEquals('atmpt_123', $result->id);
    }

    public function testMessageAttemptResend(): void
    {
        $this->mockHandler->append(new Response(204, []));

        $client = $this->createClient();
        $client->messageAttempt->resend('msg_123', 'ep_123');

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $req->getMethod());
        $this->assertStringContainsString('/msg/msg_123/endpoint/ep_123/resend', (string) $req->getUri());
    }

    // ── Health ──────────────────────────────────────────────

    public function testHealthGet(): void
    {
        $this->mockHandler->append(new Response(200, [], HealthResponse));

        $client = $this->createClient();
        $client->health->get();

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('GET', $req->getMethod());
        $this->assertStringContainsString('/v1/health', (string) $req->getUri());
    }

    // ── Statistics ──────────────────────────────────────────

    public function testStatisticsAggregateAppStats(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"success":100,"failed":5,"pending":2}'));

        $client = $this->createClient();
        $result = $client->statistics->aggregateAppStats();

        $this->assertEquals(100, $result['success']);
    }

    // ── Event Type ──────────────────────────────────────────

    public function testEventTypeList(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[{"name":"user.created","description":"User created event"}],"done":true}'));

        $client = $this->createClient();
        $result = $client->eventType->list();

        $this->assertIsArray($result);
    }

    public function testEventTypeCreate(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"name":"order.created","description":"Order created event"}'));

        $client = $this->createClient();
        $result = $client->eventType->create(new \HookSniff\Models\EventTypeIn('order.created'));

        $this->assertIsArray($result);
    }

    // ── Retry Logic ─────────────────────────────────────────

    public function testRetryOn500(): void
    {
        $this->mockHandler->append(
            new Response(500, [], '{"error":"Internal Server Error"}'),
            new Response(500, [], '{"error":"Internal Server Error"}'),
            new Response(200, [], '{"status":"ok"}')
        );

        $client = new HookSniff("token", new HookSniffOptions(retryScheduleMs: [10, 10]), $this->httpClient);
        $client->health->get();

        $this->assertCount(3, $this->requestHistory);
    }

    public function testRetryOn429(): void
    {
        $this->mockHandler->append(
            new Response(429, ['Retry-After' => '1'], '{"error":"Rate limited"}'),
            new Response(200, [], '{"status":"ok"}')
        );

        $client = new HookSniff("token", new HookSniffOptions(retryScheduleMs: [10]), $this->httpClient);
        $client->health->get();

        $this->assertCount(2, $this->requestHistory);
    }

    // ── Webhook Verification ────────────────────────────────

    public function testWebhookVerification(): void
    {
        $secret = 'whsec_' . base64_encode(random_bytes(32));
        $webhook = new \HookSniff\Webhook($secret);

        $msgId = 'msg_' . bin2hex(random_bytes(16));
        $timestamp = (string) time();
        $payload = json_encode(['event' => 'test', 'data' => ['key' => 'value']]);

        $signature = $webhook->sign($msgId, $timestamp, $payload);

        $headers = [
            'hooksniff-id' => $msgId,
            'hooksniff-timestamp' => $timestamp,
            'hooksniff-signature' => $signature,
        ];

        $result = $webhook->verify($payload, $headers);
        $this->assertEquals('test', $result['event']);
    }

    public function testWebhookVerificationFailsWithWrongSignature(): void
    {
        $this->expectException(\HookSniff\Exception\WebhookVerificationException::class);

        $secret = 'whsec_' . base64_encode(random_bytes(32));
        $webhook = new \HookSniff\Webhook($secret);

        $payload = json_encode(['event' => 'test']);
        $headers = [
            'hooksniff-id' => 'msg_123',
            'hooksniff-timestamp' => (string) time(),
            'hooksniff-signature' => 'v1,fakesignature',
        ];

        $webhook->verify($payload, $headers);
    }

    // ── API Key Auth ────────────────────────────────────────

    public function testApiKeyAuth(): void
    {
        $this->mockHandler->append(new Response(200, [], HealthResponse));

        $client = new HookSniff("sk_live_abc123", httpClient: $this->httpClient);
        $client->health->get();

        $req = $this->requestHistory[0]['request'];
        $this->assertEquals('Bearer sk_live_abc123', $req->getHeaderLine('Authorization'));
    }

    // ── Query Params ────────────────────────────────────────

    public function testQueryParams(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"data":[],"done":true}'));

        $client = $this->createClient();
        $client->message->list(options: new \HookSniff\Api\MessageListOptions(limit: 50, tag: 'test'));

        $req = $this->requestHistory[0]['request'];
        $query = $req->getUri()->getQuery();
        $this->assertStringContainsString('limit=50', $query);
        $this->assertStringContainsString('tag=test', $query);
    }

    // ── Version ─────────────────────────────────────────────

    public function testVersion(): void
    {
        $this->assertEquals('1.2.0', Version::VERSION);
    }
}
