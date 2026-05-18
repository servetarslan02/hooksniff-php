<?php
namespace HookSniff\Exception;

class TimeoutException extends \RuntimeException
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Request timeout', 0);
    }
}
