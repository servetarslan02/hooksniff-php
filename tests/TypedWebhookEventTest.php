<?php

namespace HookSniff\Tests;

use PHPUnit\Framework\TestCase;
use HookSniff\WebhookEvent;
use HookSniff\WebhookEvents\EndpointCreatedData;
use HookSniff\WebhookEvents\EndpointDisabledData;
use HookSniff\WebhookEvents\MessageAttemptExhaustedData;
use HookSniff\WebhookEvents\LastAttemptInfo;

class TypedWebhookEventTest extends TestCase
{
    public function testEndpointCreated()
    {
        $event = WebhookEvent::parse([
            'event' => 'endpoint.created',
            'data' => ['appId' => 'a1', 'endpointId' => 'e1', 'appUid' => 'u1'],
            'timestamp' => '2026-05-19',
        ]);

        $this->assertEquals('endpoint.created', $event->getEvent());
        $data = $event->parseEndpointCreatedData();
        $this->assertInstanceOf(EndpointCreatedData::class, $data);
        $this->assertEquals('a1', $data->appId);
        $this->assertEquals('e1', $data->endpointId);
        $this->assertEquals('u1', $data->appUid);
    }

    public function testEndpointDisabled()
    {
        $event = WebhookEvent::parse([
            'event' => 'endpoint.disabled',
            'data' => ['appId' => 'a1', 'endpointId' => 'e1', 'failSince' => '2026-01', 'trigger' => 'repeated-failure'],
            'timestamp' => '',
        ]);

        $data = $event->parseEndpointDisabledData();
        $this->assertInstanceOf(EndpointDisabledData::class, $data);
        $this->assertEquals('2026-01', $data->failSince);
        $this->assertEquals('repeated-failure', $data->trigger);
    }

    public function testMessageAttemptExhausted()
    {
        $event = WebhookEvent::parse([
            'event' => 'message.attempt.exhausted',
            'data' => [
                'appId' => 'a1',
                'msgId' => 'm1',
                'lastAttempt' => ['id' => 'att', 'timestamp' => 't', 'responseStatusCode' => 500],
            ],
            'timestamp' => '',
        ]);

        $data = $event->parseMessageAttemptExhaustedData();
        $this->assertInstanceOf(MessageAttemptExhaustedData::class, $data);
        $this->assertEquals('m1', $data->msgId);
        $this->assertInstanceOf(LastAttemptInfo::class, $data->lastAttempt);
        $this->assertEquals(500, $data->lastAttempt->responseStatusCode);
    }

    public function testBackwardCompat()
    {
        $event = WebhookEvent::parse([
            'event' => 'endpoint.created',
            'data' => ['appId' => 'a1'],
            'timestamp' => 't',
        ]);

        $this->assertEquals('a1', $event->get('appId'));
        $this->assertEquals('a1', $event->appId); // __get
        $this->assertEquals('endpoint.created', $event->event_type);
    }

    public function testSnakeCaseFields()
    {
        $event = WebhookEvent::parse([
            'event' => 'endpoint.created',
            'data' => ['app_id' => 'a1', 'endpoint_id' => 'e1'],
            'timestamp' => '',
        ]);

        $data = $event->parseEndpointCreatedData();
        $this->assertEquals('a1', $data->appId);
        $this->assertEquals('e1', $data->endpointId);
    }

    public function testEmptyData()
    {
        $event = WebhookEvent::parse([
            'event' => 'endpoint.created',
            'data' => [],
            'timestamp' => '',
        ]);

        $data = $event->parseEndpointCreatedData();
        $this->assertEquals('', $data->appId);
    }

    public function testEventTypeMap()
    {
        $this->assertArrayHasKey('endpoint.created', WebhookEvent::EVENT_TYPE_MAP);
        $this->assertArrayHasKey('message.attempt.exhausted', WebhookEvent::EVENT_TYPE_MAP);
        $this->assertCount(8, WebhookEvent::EVENT_TYPE_MAP);
    }
}
