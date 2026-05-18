<?php

declare(strict_types=1);

namespace HookSniff\Api;

use HookSniff\Exception\ApiException;
use HookSniff\Request\HookSniffHttpClient;

class Authentication
{
    public function __construct(
        private readonly HookSniffHttpClient $client,
    ) {
    }

    /**
     * Register a new user.
     *
     * @throws ApiException
     */
    public function register(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/register');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Login.
     *
     * @throws ApiException
     */
    public function login(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/login');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Logout the current auth token.
     *
     * @throws ApiException
     */
    public function logout(): void
    {
        $request = $this->client->newReq('POST', '/v1/auth/logout');
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Get current user info.
     *
     * @throws ApiException
     */
    public function getMe(): array
    {
        $request = $this->client->newReq('GET', '/v1/auth/me');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update user profile.
     *
     * @throws ApiException
     */
    public function updateProfile(array $body): array
    {
        $request = $this->client->newReq('PUT', '/v1/auth/profile');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Change password.
     *
     * @throws ApiException
     */
    public function changePassword(array $body): void
    {
        $request = $this->client->newReq('PUT', '/v1/auth/password');
        $request->setBody(json_encode($body));
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Forgot password — send reset email.
     *
     * @throws ApiException
     */
    public function forgotPassword(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/forgot-password');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Reset password with token.
     *
     * @throws ApiException
     */
    public function resetPassword(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/reset-password');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Verify email with token.
     *
     * @throws ApiException
     */
    public function verifyEmail(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/verify-email');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Resend verification email.
     *
     * @throws ApiException
     */
    public function resendVerification(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/resend-verification');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Refresh auth token.
     *
     * @throws ApiException
     */
    public function refreshToken(): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/refresh');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Enable 2FA.
     *
     * @throws ApiException
     */
    public function enable2fa(): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/2fa/enable');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Confirm 2FA setup.
     *
     * @throws ApiException
     */
    public function confirm2fa(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/2fa/confirm');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Disable 2FA.
     *
     * @throws ApiException
     */
    public function disable2fa(array $body): void
    {
        $request = $this->client->newReq('POST', '/v1/auth/2fa/disable');
        $request->setBody(json_encode($body));
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Get 2FA status.
     *
     * @throws ApiException
     */
    public function get2faStatus(): array
    {
        $request = $this->client->newReq('GET', '/v1/auth/2fa/status');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Verify 2FA login.
     *
     * @throws ApiException
     */
    public function verify2faLogin(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/2fa/verify');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Revoke current token.
     *
     * @throws ApiException
     */
    public function revokeToken(): void
    {
        $request = $this->client->newReq('POST', '/v1/auth/revoke-token');
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Revoke all tokens.
     *
     * @throws ApiException
     */
    public function revokeAllTokens(): void
    {
        $request = $this->client->newReq('POST', '/v1/auth/revoke-all-tokens');
        $this->client->sendNoResponseBody($request);
    }

    /**
     * Get consent info.
     *
     * @throws ApiException
     */
    public function getConsent(): array
    {
        $request = $this->client->newReq('GET', '/v1/auth/consent');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Update consent.
     *
     * @throws ApiException
     */
    public function updateConsent(array $body): array
    {
        $request = $this->client->newReq('POST', '/v1/auth/consent');
        $request->setBody(json_encode($body));
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Export user data (GDPR).
     *
     * @throws ApiException
     */
    public function exportData(): array
    {
        $request = $this->client->newReq('GET', '/v1/auth/export');
        $res = $this->client->send($request);

        return json_decode($res, true);
    }

    /**
     * Delete account.
     *
     * @throws ApiException
     */
    public function deleteAccount(array $body): void
    {
        $request = $this->client->newReq('DELETE', '/v1/auth/account');
        $request->setBody(json_encode($body));
        $this->client->sendNoResponseBody($request);
    }
}
