<?php
namespace HookSniff\Exception;

class AuthenticationException extends HookSniffApiException
{
    public function __construct(?string $message = null, array $headers = [])
    {
        parent::__construct($message ?? 'Authentication failed', 401, $message, $headers);
    }
}
