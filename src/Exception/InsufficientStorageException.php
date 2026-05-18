<?php
namespace HookSniff\Exception;

class InsufficientStorageException extends HookSniffApiException
{
    public function __construct(?string $message = null, array $headers = [])
    {
        parent::__construct($message ?? 'Insufficient storage', 507, $message, $headers);
    }
}
