<?php

namespace HookSniff\Request;



use GuzzleHttp\Client;

class HookSniffRequestContext
{
    /** @var string The API base URL, for example "https://hooksniff-api-1046140057667.europe-west1.run.app" */
    public string $baseUrl;
    /** @var string The 'bearer' scheme access token */
    public string $token;
    /** @var int|null Time in milliseconds to wait for requests to get a response. */
    public ?int $timeout;

    public function __construct(string $baseUrl, string $token, ?int $timeout = null)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        $this->timeout = $timeout;
    }
}
