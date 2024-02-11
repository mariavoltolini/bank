<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;

class ApiService
{
    public function __construct(private PendingRequest $http)
    {
    }
    public function post(string $url, array $body): ?Response
    {
        $response = $this->http->post($url, $body);

        return $response;
    }
}
