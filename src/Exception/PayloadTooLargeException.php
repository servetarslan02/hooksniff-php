<?php
namespace HookSniff\Exception;

class PayloadTooLargeException extends HookSniffApiException
{
    public function __construct(?string $message = null, array $headers = [])
    {
        parent::__construct($message ?? 'Payload too large', 413, $message, $headers);
    }
}
