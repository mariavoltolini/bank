<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BalanceUserVerificationService
{
    public function verify(float $balance, float $transactionValue): void
    {
        if ($transactionValue > $balance) {
            $response = new JsonResponse([
                'message' => 'Insufficient balance!',
            ], 403);

            throw new HttpResponseException($response);
        }
    }
}
