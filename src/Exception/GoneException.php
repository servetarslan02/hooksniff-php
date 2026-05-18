<?php
namespace HookSniff\Exception;

class GoneException extends HookSniffApiException
{
    public function __construct(?string $message = null, array $headers = [])
    {
        parent::__construct($message ?? 'Gone', 410, $message, $headers);
    }
}
