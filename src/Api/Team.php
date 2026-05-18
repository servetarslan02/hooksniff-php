<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Team
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * List all teams.
     *
     * @throws ApiException
     */
    public function list(): array
    {
        $request = $this->client->newReq('GET', '/api/v1/teams');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Create a new team.
     *
     * @throws ApiException
     */
    public function create(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/teams');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Get a team by ID.
     *
     * @throws ApiException
     */
    public function get(string $id): array
    {
        $request = $this->client->newReq('GET', "/api/v1/teams/{$id}");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Accept a team invite.
     *
     * @throws ApiException
     */
    public function acceptInvite(array $body): array
    {
        $request = $this->client->newReq('POST', '/api/v1/teams/accept-invite');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Invite a member to a team.
     *
     * @throws ApiException
     */
    public function inviteMember(string $teamId, array $body): array
    {
        $request = $this->client->newReq('POST', "/api/v1/teams/{$teamId}/invite");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * List members of a team.
     *
     * @throws ApiException
     */
    public function listMembers(string $teamId): array
    {
        $request = $this->client->newReq('GET', "/api/v1/teams/{$teamId}/members");
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Remove a member from a team.
     *
     * @throws ApiException
     */
    public function removeMember(string $teamId, string $userId): void
    {
        $request = $this->client->newReq('DELETE', "/api/v1/teams/{$teamId}/members/{$userId}");
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Change a member's role in a team.
     *
     * @throws ApiException
     */
    public function changeRole(string $teamId, string $userId, array $body): array
    {
        $request = $this->client->newReq('PUT', "/api/v1/teams/{$teamId}/members/{$userId}/role");
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }
}
