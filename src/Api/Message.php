<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Models\ListResponseMessageOut;
use HookSniff\Models\MessageIn;
use HookSniff\Models\MessageOut;
use HookSniff\Request\HookSniffHttpClient;

class Message
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all messages.
     *
     * @throws ApiException
     */
    public function list(
        ?string $appId = null,
        ?MessageListOptions $options = null,
    ): ListResponseMessageOut {
        $path = $appId !== null ? "/v1/webhooks" : '/v1/msg';
        $request = $this->client->newReq('GET', $path);
        if (null !== $options) {
            $request->setQueryParam('limit', $options->limit);
            $request->setQueryParam('iterator', $options->iterator);
            $request->setQueryParam('channel', $options->channel);
            $request->setQueryParam('before', $options->before);
            $request->setQueryParam('after', $options->after);
            $request->setQueryParam('with_content', $options->withContent);
            $request->setQueryParam('tag', $options->tag);
            $request->setQueryParam('event_types', $options->eventTypes);
        }
        $res = $this->client->send($request);

        return ListResponseMessageOut::fromJson($res);
    }

    /**
     * Auto-paginate through all messages.
     *
     * Returns a Generator that automatically fetches the next page.
     *
     * @param string|null $appId Application ID
     * @param int|null $limit Page size
     * @return \Generator<MessageOut>
     *
     * @example
     *   foreach ($hs->message->listAll(limit: 100) as $msg) {
     *       echo $msg->id;
     *   }
     */
    public function listAll(
        ?string $appId = null,
        ?int $limit = null,
    ): \Generator {
        return \HookSniff\Paginator::paginate(
            fn($opts) => $this->list($appId, $opts),
            $limit,
        );
    }

    /**
     * Create a new message and dispatch it to all matching endpoints.
     *
     * @throws ApiException
     */
    public function create(
        MessageIn $messageIn,
        ?string $appId = null,
        ?MessageCreateOptions $options = null,
    ): MessageOut {
        $path = $appId !== null ? "/v1/webhooks" : '/v1/msg';
        $request = $this->client->newReq('POST', $path);
        if (null !== $options) {
            $request->setQueryParam('with_content', $options->withContent);
            $request->setHeaderParam('idempotency-key', $options->idempotencyKey);
        }
        $request->setBody(json_encode($messageIn));
        $res = $this->client->send($request);

        return MessageOut::fromJson($res);
    }

    /**
     * Get a message by its ID or eventID.
     *
     * @throws ApiException
     */
    public function get(
        string $msgId,
        ?string $appId = null,
        ?MessageGetOptions $options = null,
    ): MessageOut {
        $path = $appId !== null ? "/v1/webhooks/{$msgId}" : "/v1/msg/{$msgId}";
        $request = $this->client->newReq('GET', $path);
        if (null !== $options) {
            $request->setQueryParam('with_content', $options->withContent);
        }
        $res = $this->client->send($request);

        return MessageOut::fromJson($res);
    }
}
