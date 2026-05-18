<?php
namespace HookSniff\Exception;

class NotImplementedException extends HookSniffApiException
{
    public function __construct(?string $message = null, array $headers = [])
    {
        parent::__construct($message ?? 'Not implemented', 501, $message, $headers);
    }
}
