<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class TransactionVerificationService
{
    public function __construct(private ApiService $apiServ)
    {
    }

    public function verify(array $transaction): void
    {
        try
        {
            $transactionAuthUrl = env('TRANSACTION_AUTH_URL');

            $return = $this->apiServ->post($transactionAuthUrl, $transaction);

            if ($return->successful()) {
                $jsonData = $return->json();

                if ($jsonData['message'] === 'Autorizado') {
                    return;
                }
            }

            $response = new JsonResponse([
                'message' => 'Unauthorized transaction!',
            ], 403);
            throw new HttpResponseException($response);

        } catch (\Exception $e) {
            $response = new JsonResponse([
                'message' => 'Unauthorized transaction!',
            ], 403);
            throw new HttpResponseException($response);
        }
    }
}
