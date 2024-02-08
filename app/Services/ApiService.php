<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiService
{
    public function post(string $url, array $body): ?Response
    {
        $response = Http::post($url, $body);

        return $response;
    }
}
