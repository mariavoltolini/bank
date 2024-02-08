<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class TransactionAuthorizationVerificationService
{
    public function __construct(private ApiService $apiService)
    {
    }

    public function verify(array $transaction): ?bool
    {
        $transactionAuthUrl = env('TRANSACTION_AUTH_URL');

        $return = $this->apiService->post($transactionAuthUrl, $transaction);

        if ($return->successful()) {
            $jsonData = $return->json();

            if ($jsonData['message'] === 'Autorizado') {
                return true;
            }
        }

        $response = new JsonResponse([
            'message' => 'Unauthorized transaction!',
        ], 400);

        throw new HttpResponseException($response);
    }
}
