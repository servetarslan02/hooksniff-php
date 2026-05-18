<?php
namespace HookSniff\Exception;

class RequestTimeoutException extends HookSniffApiException
{
    public function __construct(?string $message = null, array $headers = [])
    {
        parent::__construct($message ?? 'Request timeout', 408, $message, $headers);
    }
}
