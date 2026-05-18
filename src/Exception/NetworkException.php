<?php
namespace HookSniff\Exception;

class NetworkException extends \RuntimeException
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Network error', 0);
    }
}
