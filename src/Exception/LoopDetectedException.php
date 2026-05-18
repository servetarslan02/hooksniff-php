<?php
namespace HookSniff\Exception;

class LoopDetectedException extends HookSniffApiException
{
    public function __construct(?string $message = null, array $headers = [])
    {
        parent::__construct($message ?? 'Loop detected', 508, $message, $headers);
    }
}
