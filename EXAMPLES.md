# HookSniff PHP SDK — Kullanım Örnekleri

## Kurulum

```bash
composer require hooksniff/hooksniff:^1.2
```

## Başlangıç

```php
require 'vendor/autoload.php';

use HookSniff\HookSniff;
use HookSniff\HookSniffOptions;
use HookSniff\Models\MessageIn;
use HookSniff\Models\EndpointIn;

// API Key ile başlat
$client = new HookSniff("sk_live_xxx");

// Özel sunucu URL'si
$options = new HookSniffOptions(
    serverUrl: 'https://your-instance.hooksniff.com'
);
$client = new HookSniff("sk_live_xxx", $options);
```

## Endpoint Yönetimi

```php
// Tüm endpoint'leri listele
$endpoints = $client->endpoint->list();

// Yeni endpoint oluştur
$endpoint = $client->endpoint->create(
    new EndpointIn("https://example.com/webhook")
);

// Endpoint detayı
$endpoint = $client->endpoint->get("ep_123");

// Endpoint güncelle
$client->endpoint->update("ep_123", new EndpointUpdate("https://new-url.com/webhook"));

// Endpoint sil
$client->endpoint->delete("ep_123");

// Signing secret al
$secret = $client->endpoint->getSecret("ep_123");

// Secret rotate et
$client->endpoint->rotateSecret("ep_123", new EndpointSecretRotateIn());
```

## Webhook Gönderme

```php
use HookSniff\Models\MessageIn;

// Basit webhook
$message = $client->message->create(
    new MessageIn("user.created", [
        "email" => "user@example.com",
        "name" => "Test User"
    ])
);

// Uygulama bazlı webhook
$message = $client->message->create(
    new MessageIn("order.completed", ["order_id" => "12345"]),
    "app_123"
);

// Mesajları listele
$messages = $client->message->list();

// Mesaj detayı
$message = $client->message->get("msg_123");
```

## Delivery Takibi

```php
// Endpoint'e göre attempt'ler
$attempts = $client->messageAttempt->listByEndpoint("ep_123");

// Mesaja göre attempt'ler
$attempts = $client->messageAttempt->listByMsg("msg_123");

// Attempt detayı
$attempt = $client->messageAttempt->get("msg_123", "atmpt_456");

// Tekrar gönder
$client->messageAttempt->resend("msg_123", "ep_123");
```

## Webhook Doğrulama

```php
use HookSniff\Webhook;

$wh = new Webhook("whsec_xxx");

$headers = [
    'hooksniff-id' => $_SERVER['HTTP_HOOKSNIFF_ID'],
    'hooksniff-timestamp' => $_SERVER['HTTP_HOOKSNIFF_TIMESTAMP'],
    'hooksniff-signature' => $_SERVER['HTTP_HOOKSNIFF_SIGNATURE'],
];

try {
    $payload = $wh->verify(file_get_contents('php://input'), $headers);
    // Webhook geçerli, payload'ı işle
    echo $payload['event']; // "user.created"
} catch (\HookSniff\Exception\WebhookVerificationException $e) {
    // Geçersiz webhook
    http_response_code(400);
}
```

## Alert Yönetimi

```php
// Alert'leri listele
$alerts = $client->alert->list();

// Yeni alert oluştur
$alert = $client->alert->create([
    'name' => 'Başarısız Teslimat Uyarısı',
    'condition' => 'failure_rate',
    'threshold' => 10,
    'channels' => ['email']
]);

// Alert'i test et
$client->alert->test("alert_123");
```

## Billing & Abonelik

```php
// Abonelik bilgisi
$sub = $client->billing->getSubscription();
echo $sub['plan']; // "pro"

// Kullanım bilgisi
$usage = $client->billing->getUsage();
echo $usage['webhooks']['used']; // 500

// Faturalar
$invoices = $client->billing->getInvoices();

// Plan yükseltme
$client->billing->upgrade(['plan' => 'business']);

// Portal aç
$portal = $client->billing->openPortal();
header("Location: " . $portal['url']);
```

## Takım Yönetimi

```php
// Takımları listele
$teams = $client->team->list();

// Üye davet et
$client->team->inviteMember("team_123", [
    'email' => 'new-member@example.com',
    'role' => 'member'
]);

// Üyeleri listele
$members = $client->team->listMembers("team_123");

// Üyeyi çıkar
$client->team->removeMember("team_123", "user_456");

// Rol değiştir
$client->team->changeRole("team_123", "user_456", ['role' => 'admin']);
```

## API Key Yönetimi

```php
// API key'leri listele
$keys = $client->apiKey->list();

// Yeni key oluştur
$key = $client->apiKey->create(['name' => 'Production Key']);
echo $key['key']; // "sk_live_xxx"

// Key rotate et
$newKey = $client->apiKey->rotate("key_123");

// Key sil
$client->apiKey->delete("key_123");
```

## Environment (Ortam Değişkenleri)

```php
// Ortamları listele
$envs = $client->environment->list();

// Yeni ortam oluştur
$env = $client->environment->create(['name' => 'production']);

// Değişkenleri listele
$vars = $client->environment->listVariables("env_123");

// Değişken oluştur
$client->environment->createVariable("env_123", [
    'key' => 'API_URL',
    'value' => 'https://api.example.com'
]);

// Toplu değişken güncelle
$client->environment->bulkUpsertVariables("env_123", [
    'variables' => [
        ['key' => 'DB_HOST', 'value' => 'localhost'],
        ['key' => 'DB_PORT', 'value' => '5432']
    ]
]);
```

## Analytics

```php
// Teslimat trendi
$trend = $client->analytics->deliveryTrend([
    'start_date' => '2026-05-01',
    'end_date' => '2026-05-18'
]);

// Başarı oranı
$rate = $client->analytics->successRate();

// Gecikme trendi
$latency = $client->analytics->latencyTrend();
```

## SSO

```php
// SSO config al
$config = $client->sso->getConfig();

// SSO config güncelle
$client->sso->upsertConfig([
    'provider' => 'saml',
    'entity_id' => 'https://idp.example.com',
    'sso_url' => 'https://idp.example.com/sso',
    'certificate' => '-----BEGIN CERTIFICATE-----...'
]);

// SSO bağlantısını test et
$result = $client->sso->testConnection();
```

## Custom Domain

```php
// Domain'leri listele
$domains = $client->customDomain->list();

// Domain ekle
$domain = $client->customDomain->add(['domain' => 'hooks.example.com']);
echo $domain['cname_target']; // DNS CNAME hedefi

// Domain doğrula
$result = $client->customDomain->verify("cd_123");
```

## Rate Limiting

```php
// Rate limit'leri listele
$limits = $client->rateLimit->list();

// Rate limit ayarla
$client->rateLimit->set("ep_123", [
    'requests_per_minute' => 100
]);

// Rate limit sil
$client->rateLimit->delete("ep_123");
```

## Admin İşlemleri

```php
// Sistem istatistikleri
$stats = $client->admin->getSystemStats();

// Kullanıcıları listele
$users = $client->admin->listUsers(['page' => 1]);

// Kullanıcı detayı
$user = $client->admin->getUserDetail("user_123");

// Plan değiştir
$client->admin->changePlan("user_123", ['plan' => 'pro']);

// Kullanıcı adına giriş yap
$impersonate = $client->admin->impersonateUser("user_123");
echo $impersonate['token'];

// Gelir raporu
$revenue = $client->admin->getRevenue();

// Platform ayarları
$settings = $client->admin->getSettings();
```

## Bildirimler

```php
// Bildirimleri listele
$notifications = $client->notification->list();

// Okunmamış sayısı
$count = $client->notification->unreadCount();

// Tümünü okundu işaretle
$client->notification->markAllRead();

// Tek bildirimi okundu işaretle
$client->notification->markRead("notif_123");
```

## Arama

```php
// Teslimat ara
$results = $client->search->search([
    'q' => 'user.created',
    'limit' => 20
]);
```

## Schema (Şema Doğrulama)

```php
// Şemaları listele
$schemas = $client->schema->list();

// Şema kaydet
$schema = $client->schema->register([
    'name' => 'user.created',
    'schema' => [
        'type' => 'object',
        'properties' => [
            'email' => ['type' => 'string', 'format' => 'email']
        ],
        'required' => ['email']
    ]
]);

// Event doğrula
$result = $client->schema->validate("schema_123", [
    'event_type' => 'user.created',
    'payload' => ['email' => 'test@example.com']
]);
```

## Stream (Gerçek Zamanlı)

```php
// Kanalları listele
$channels = $client->stream->listChannels();

// Mesaj yayınla
$client->stream->publish([
    'channel_id' => 'ch_123',
    'data' => ['event' => 'user.active']
]);

// Abonelikleri listele
$subs = $client->stream->listSubscriptions();
```

## Background Task

```php
// Görevleri listele
$tasks = $client->backgroundTask->list();

// Görev detayı
$task = $client->backgroundTask->get("bt_123");

// Görevi iptal et
$client->backgroundTask->cancel("bt_123");
```

## Operational Webhook

```php
// Endpoint'leri listele
$endpoints = $client->operationalWebhook->list();

// Yeni endpoint oluştur
$endpoint = $client->operationalWebhook->create([
    'url' => 'https://example.com/ops-webhook',
    'events' => ['delivery.failed', 'endpoint.disabled']
]);

// Teslimatları listele
$deliveries = $client->operationalWebhook->listDeliveries("ow_123");
```

## Connector & Integration

```php
// Connector'ları listele
$connectors = $client->connector->list();

// Integration'ları listele
$integrations = $client->integration->list();

// Integration test et
$client->integration->test("integ_123");
```

## Hata Yönetimi

```php
use HookSniff\Exception\ApiException;

try {
    $client->endpoint->get("nonexistent");
} catch (ApiException $e) {
    echo "HTTP Kodu: " . $e->getCode();    // 404
    echo "Hata: " . $e->getMessage();       // {"code": 404, "message": "Not found"}
}
```

## Debug Modu

```php
$options = new HookSniffOptions(debug: true);
$client = new HookSniff("sk_live_xxx", $options);
```

Terminal'de:
```bash
HOOKSNIFF_DEBUG=1 php your_script.php
```
