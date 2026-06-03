# HookSniff PHP SDK

<p align="center">
  <a href="https://packagist.org/packages/hooksniff/hooksniff"><img src="https://img.shields.io/packagist/v/hooksniff/hooksniff" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/hooksniff/hooksniff"><img src="https://img.shields.io/packagist/dt/hooksniff/hooksniff" alt="Total Downloads"></a>
  <a href="https://github.com/servetarslan02/hooksniff-php/blob/main/LICENSE"><img src="https://img.shields.io/github/license/servetarslan02/hooksniff-php" alt="License"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/php-%3E%3D8.1-777BB4" alt="PHP Version"></a>
</p>

Official PHP SDK for the [HookSniff](https://hooksniff.vercel.app) webhook delivery platform.

## Installation

```bash
composer require hooksniff/hooksniff
```

## Quick Start

```php
require 'vendor/autoload.php';

use HookSniff\HookSniff;
use HookSniff\Models\MessageIn;
use HookSniff\Models\EndpointIn;

// Initialize client
$client = new HookSniff("hs_xxx");

// Create an endpoint
$endpoint = $client->endpoint->create(
    new EndpointIn("https://example.com/webhook")
);

// Send a webhook
$message = $client->message->create(
    new MessageIn("user.created", ["email" => "user@example.com"])
);

// List deliveries
$attempts = $client->messageAttempt->listByEndpoint($endpoint->id);
```

## Webhook Verification

```php
use HookSniff\Webhook;

$wh = new Webhook("whsec_xxx");

$headers = [
    'hooksniff-id' => $_SERVER['HTTP_HOOKSNIFF_ID'],
    'hooksniff-timestamp' => $_SERVER['HTTP_HOOKSNIFF_TIMESTAMP'],
    'hooksniff-signature' => $_SERVER['HTTP_HOOKSNIFF_SIGNATURE'],
];

$payload = $wh->verify(file_get_contents('php://input'), $headers);
```

## Authentication

```php
use HookSniff\HookSniff;

// API Key authentication
$client = new HookSniff("sk_live_xxx");

// Custom server URL
use HookSniff\HookSniffOptions;

$options = new HookSniffOptions(
    serverUrl: 'https://your-instance.hooksniff.com'
);
$client = new HookSniff("sk_live_xxx", $options);
```

## Resources

| Resource | Methods | Description |
|----------|---------|-------------|
| **Admin** | listUsers, getUserDetail, changePlan, changeStatus, impersonateUser, getSystemStats, getRevenue, getSettings, updateSettings | Admin panel operations |
| **Alert** | list, create, get, update, delete, test | Alert management |
| **Analytics** | deliveryTrend, successRate, latencyTrend | Delivery analytics |
| **ApiKey** | list, create, delete, rotate | API key management |
| **Application** | list, create, get, update, delete | Application management |
| **Authentication** | register, login, logout, getMe, updateProfile, changePassword, forgotPassword, resetPassword, verifyEmail, enable2fa, confirm2fa, disable2fa, get2faStatus | Auth & 2FA |
| **AuditLog** | list, get | Audit trail |
| **BackgroundTask** | list, get, cancel | Background task management |
| **Billing** | getSubscription, cancelSubscription, upgrade, openPortal, getUsage, getInvoices, getOverageSettings | Billing & subscriptions |
| **Connector** | list, get, listConfigs, createConfig, getConfig, updateConfig, deleteConfig | Connector management |
| **CustomDomain** | list, add, delete, verify | Custom domain management |
| **Device** | list, register, delete | Push device management |
| **Endpoint** | list, create, get, update, delete, patch, getHeaders, updateHeaders, patchHeaders, getSecret, rotateSecret, sendExample | Endpoint CRUD |
| **Environment** | list, create, get, update, delete, listVariables, createVariable, updateVariable, deleteVariable, bulkUpsertVariables | Environment & variables |
| **EventType** | list, create, get, update, delete, patch, importOpenapi | Event type management |
| **Health** | get | Health check |
| **Inbound** | listConfigs, createConfig, updateConfig, deleteConfig, handle | Inbound webhook configs |
| **Integration** | list, get, create, update, delete, test, listEvents, getStats | Integration management |
| **Message** | list, create, get | Message dispatch |
| **MessageAttempt** | listByEndpoint, listByMsg, get, resend | Delivery attempts |
| **MessagePoller** | poll, seek, commit | Message polling |
| **Notification** | list, unreadCount, markAllRead, markRead, delete | Notifications |
| **OperationalWebhook** | list, create, get, update, delete, listDeliveries | Operational webhooks |
| **Portal** | getConfig, updateConfig, getProfile, getUsage, getEmbedCode | Customer portal |
| **RateLimit** | list, get, set, delete | Rate limiting |
| **Routing** | get, update, getHealth | Endpoint routing |
| **Schema** | list, register, get, validate | Schema registry |
| **Search** | search | Delivery search |
| **ServiceToken** | list, create, delete, update, reveal | Service tokens |
| **Sso** | getConfig, upsertConfig, deleteConfig, testConnection | SSO configuration |
| **Statistics** | aggregateAppStats | App statistics |
| **Stream** | listChannels, getChannel, createChannel, updateChannel, deleteChannel, listMessages, listSubscriptions, disconnectSubscription, publish | Real-time streaming |
| **Team** | list, create, get, acceptInvite, inviteMember, listMembers, removeMember, changeRole | Team management |
| **Template** | list, get, apply | Webhook templates |
| **Transform** | list, create, update, delete, test | Payload transforms |

## Error Handling

```php
use HookSniff\Exception\ApiException;

try {
    $client->endpoint->get("nonexistent_id");
} catch (ApiException $e) {
    echo $e->getCode();    // HTTP status code
    echo $e->getMessage(); // Error message
}
```

## Configuration

```php
use HookSniff\HookSniffOptions;

$options = new HookSniffOptions(
    serverUrl: 'https://hooksniff-api-e6ztf3x2ma-ew.a.run.app',  // Custom server
    timeoutMs: 30000,                          // Request timeout
    numRetries: 2,                             // Retry count
    retryScheduleMs: [60, 120, 240],          // Retry delays
    debug: false,                              // Debug mode
);

$client = new HookSniff("sk_live_xxx", $options);
```

## Debug Mode

```bash
HOOKSNIFF_DEBUG=1 php your_script.php
```

## Examples

See [EXAMPLES.md](EXAMPLES.md) for detailed usage examples covering all 35 resources.

## Links

- [Documentation](https://hooksniff.vercel.app/docs)
- [API Reference](https://hooksniff.vercel.app/docs)
- [GitHub](https://github.com/servetarslan02/hooksniff-php)
- [Packagist](https://packagist.org/packages/hooksniff/hooksniff)

## License

MIT
